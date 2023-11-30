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

        // Récupérer l'ID de l'auteur associé au livre
        $authorIdQuery = "SELECT idauteur FROM livre WHERE id = $bookId";
        $result = $conn->query($authorIdQuery);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $authorId = $row['idauteur'];

            // Requête pour compter le nombre de livres liés à cet auteur
            $countAuthorsQuery = "SELECT COUNT(*) AS authorCount FROM livre WHERE idauteur = $authorId";
            $resultAuthors = $conn->query($countAuthorsQuery);

            if ($resultAuthors && $resultAuthors->num_rows > 0) {
                $rowAuthors = $resultAuthors->fetch_assoc();
                $authorCount = $rowAuthors['authorCount'];

                // Si l'auteur est le seul associé à ce livre, supprimer l'auteur
                if ($authorCount === 1) {
                    $deleteAuthorQuery = "DELETE FROM auteur WHERE id = $authorId";
                    $conn->query($deleteAuthorQuery);
                }
            }

            // Requête pour supprimer le livre
            $deleteBookQuery = "DELETE FROM livre WHERE id = $bookId";

            // Exécutez la requête pour supprimer le livre
            if ($conn->query($deleteBookQuery) === TRUE) {
                // Redirection vers la page d'origine après la suppression réussie
                if (isset($_GET['from'])) {
                    $redirectTo = urldecode($_GET['from']);
                    header("Location: $redirectTo");
                    exit();
                } else {
                    // Redirigez l'utilisateur vers une page par défaut si l'URL d'origine n'est pas spécifiée
                    header("Location: ../index.php");
                    exit();
                }
            } else {
                // Gérez les erreurs en affichant un message à l'utilisateur
                echo 'Erreur lors de la suppression du livre : ' . $conn->error;
            }
        } else {
            echo 'Erreur lors de la récupération de l\'ID de l\'auteur associé au livre.';
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