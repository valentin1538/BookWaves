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

        // Requête pour supprimer le livre
        $deleteBookQuery = "DELETE FROM livreperso WHERE id = $bookId";

        // Exécutez la requête pour supprimer le livre
        if ($conn->query($deleteBookQuery) === TRUE) {
            // Redirection vers la page d'origine après la suppression réussie
            if (isset($_GET['from'])) {
                $redirectTo = urldecode($_GET['from']);
                header("Location: $redirectTo");
                exit();
            } else {
                // Redirigez l'utilisateur vers une page par défaut si l'URL d'origine n'est pas spécifiée
                header("Location: ../apges_perso/livre_perso.php");
                exit();
            }
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