<?php
// Connexion à la base de données (utilisez vos propres informations de connexion)
$servername = "localhost";
$username = "root";
$password = "";
$database = "Biblio";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Vérifiez si le paramètre genreId est présent dans la requête GET
if (isset($_GET['genreId'])) {
    $genreId = $_GET['genreId'];

    // Requête pour récupérer le nom du genre sélectionné
    $queryGenreName = "SELECT nom FROM genre WHERE id = $genreId";
    $resultGenreName = $conn->query($queryGenreName);

    if ($resultGenreName && $resultGenreName->num_rows > 0) {
        $rowGenreName = $resultGenreName->fetch_assoc();
        $genreName = $rowGenreName['nom'];

        // Requête pour récupérer les livres du genre spécifié
        $queryLivresGenre = "SELECT id, nom, infos FROM livre WHERE idgenre = $genreId";
        $resultLivresGenre = $conn->query($queryLivresGenre);

        if ($resultLivresGenre && $resultLivresGenre->num_rows > 0) {
            echo "<h3>$genreName</h3>";
            echo '<div class="container">';
            while ($rowLivre = $resultLivresGenre->fetch_assoc()) {
                $livreId = $rowLivre['id'];
                $livreNom = $rowLivre['nom'];
                $livreInfos = $rowLivre['infos'];

                echo '<div class="grid-item">';
                echo '<div class="darkblue-header">';
                echo "<p>$livreNom</p>"; // Nom Livre
                echo '</div>';
                echo "<p>Auteur: Aucun</p>"; // Auteur
                echo '<div class="pull-left">';
                echo '<h5><i class="fa fa-hdd-o"></i></h5>';
                echo '</div>';
                echo '<div class="pull-right">';
                echo "<h5>Format : Epub</h5>";  // format
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo "Aucun livre trouvé pour ce genre.";
        }
    } else {
        echo "Nom du genre non trouvé.";
    }
} else {
    echo "Paramètre genreId manquant.";
}

$conn->close();
?>
