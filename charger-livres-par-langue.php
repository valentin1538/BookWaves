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

// Vérifiez si le paramètre langueId est présent dans la requête GET
if (isset($_GET['langueId'])) {
    $langueId = $_GET['langueId'];

    // Requête pour récupérer le nom de la langue sélectionnée
    $queryLangueName = "SELECT nom FROM langue WHERE id = $langueId";
    $resultLangueName = $conn->query($queryLangueName);

    if ($resultLangueName && $resultLangueName->num_rows > 0) {
        $rowLangueName = $resultLangueName->fetch_assoc();
        $langueName = $rowLangueName['nom'];

        // Requête pour récupérer les livres de la langue spécifiée
        $queryLivresLangue = "SELECT livre.id AS id, livre.nom AS nom, auteur.nom AS auteur, editeur.nom AS editeur, genre.nom AS genre, langue.nom AS langue, livre.infos AS infos 
        FROM livre 
        JOIN auteur ON livre.idauteur = auteur.id 
        JOIN editeur ON livre.idediteur = editeur.id 
        JOIN genre ON livre.idgenre = genre.id 
        JOIN langue ON livre.idlangue = langue.id  WHERE idlangue = $langueId";
        $resultLivresLangue = $conn->query($queryLivresLangue);

        if ($resultLivresLangue && $resultLivresLangue->num_rows > 0) {
            echo "<h3>$langueName</h3>";
            echo '<div class="container">';
            while ($rowLivre = $resultLivresLangue->fetch_assoc()) {
                $livreId = $rowLivre['id'];
                $livreNom = $rowLivre['nom'];
                $livreAuteur = $rowLivre['auteur'];
                $livreEditeur = $rowLivre['editeur'];
                $livreGenre = $rowLivre['genre'];
                $livreLangue = $rowLivre['langue'];
                $livreInfos = $rowLivre['infos'];

                echo '<div class="book">';
                echo '<div class="title-bar">';
                echo "<h2> $livreNom </h2>";
                echo '<div id="header_ajout_livre_bar" class="dropdown bars">';
                echo '<a data-toggle="dropdown" class="dropdown-toggle" href="#">';
                echo '<i class="fa-solid fa-bars"></i>';
                echo '</a>';
                echo '<ul class="dropdown-menu extended notification">';
                echo '<div class="notify-arrow notify-arrow-green"></div>';
                echo '<li>';
                echo '<a href="#"><i class="fa fa-eye"></i> Visualiser</a>';
                echo '</li>';
                echo '<li>';
                echo '<a href="#"><i class="fa fa-pencil"></i> Modifier</a>';
                echo '</li>';
                echo '<li>';
                echo '<a href="#"><i class="fa fa-arrows-rotate"></i> Convertir</a>';
                echo '</li>';
                echo '<li>';
                echo '<a href="#"><i class="fa fa-trash"></i> Supprimer</a>';
                echo '</li>';
                echo '</ul>';
                echo '</div>';
                echo '</div>';
                echo "<p><strong>Auteur :</strong> $livreAuteur </p>";
                echo "<p><strong>Éditeur :</strong> $livreEditeur </p>";
                echo "<p><strong>Genre :</strong> $livreGenre </p>";
                echo "<p><strong>Langue :</strong> $livreLangue </p>";
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo "Aucun livre trouvé pour cette langue.";
        }
    } else {
        echo "Nom de la langue non trouvé.";
    }
} else {
    echo "Paramètre langueId manquant.";
}

$conn->close();
?>
