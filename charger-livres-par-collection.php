<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "Biblio";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
   die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Vérifiez si le paramètre collectionId est présent dans la requête GET
if (isset($_GET['collectionId'])) {
    $collectionId = $_GET['collectionId'];
 
    // Requête pour récupérer le nom de la collection sélectionnée
    $queryCollectionName = "SELECT nom FROM collection WHERE id = $collectionId";
    $resultCollectionName = $conn->query($queryCollectionName);
 
    if ($resultCollectionName && $resultCollectionName->num_rows > 0) {
        $rowCollectionName = $resultCollectionName->fetch_assoc();
        $collectionName = $rowCollectionName['nom'];
 
        // Requête pour récupérer les livres de la collection spécifiée
        $queryLivresCollection = "SELECT livre.id AS id, livre.nom AS nom, auteur.nom AS auteur, editeur.nom AS editeur, genre.nom AS genre, langue.nom AS langue, livre.infos AS infos 
        FROM livre 
        JOIN auteur ON livre.idauteur = auteur.id 
        JOIN editeur ON livre.idediteur = editeur.id 
        JOIN genre ON livre.idgenre = genre.id 
        JOIN langue ON livre.idlangue = langue.id WHERE idcollection = $collectionId + 1";
        $resultLivresCollection = $conn->query($queryLivresCollection);
 

        
        if ($resultLivresCollection && $resultLivresCollection->num_rows > 0) {
            echo "<h3>$collectionName</h3>";
            echo '<div class="container">';
            while ($rowLivre = $resultLivresCollection->fetch_assoc()) {
                $livreId = $rowLivre['id'];
                $livreNom = $rowLivre['nom'];
                $livreAuteur = $rowLivre['auteur'];
                $livreEditeur = $rowLivre['editeur'];
                $livreGenre = $rowLivre['genre'];
                $livreLangues = $rowLivre['langue'];
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
                echo "<p><strong>Langue :</strong> $livreLangues </p>";
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo "Aucun livre trouvé pour cette collection.";
        }
    } else {
        echo "Nom de la collection non trouvé.";
    }
 } else {
    echo "Paramètre collectionId manquant.";
 }
 
 $conn->close();
 
