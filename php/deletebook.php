<?php
// deletebook.php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Récupérer l'ID du livre à supprimer depuis la requête
    $bookId = $_GET['id'] ?? '';

    // Vérifier si l'ID du livre est valide
    if (!empty($bookId) && is_numeric($bookId)) {
        // Connexion à la base de données MySQL
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "Biblio";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Vérification de la connexion
        if ($conn->connect_error) {
            die("La connexion a échoué : " . $conn->connect_error);
        }

        // Requête SQL pour supprimer le livre
        $delete_query = "DELETE FROM livre WHERE id = $bookId";

        if ($conn->query($delete_query) === TRUE) {
            // Redirection vers la page d'origine après la suppression réussie
            header("Location: index.php");
            exit();
        } else {
            // Gérez les erreurs en affichant un message à l'utilisateur
            echo 'Erreur lors de la suppression du livre : ' . $conn->error;
        }

        // Fermeture de la connexion à la base de données
        $conn->close();
    } else {
        // Gérez les erreurs si l'ID n'est pas valide
        echo 'ID de livre non valide.';
    }
} else {
    // Gérez les erreurs si la méthode HTTP n'est pas correcte
    echo 'Méthode HTTP incorrecte.';
}
?>
