<?php
// Connexion à la base de données (utilisez vos propres informations de connexion)
$servername = "localhost";
$username = "root";
$password = "";
$database = "Biblio";
$conn = new mysqli($servername, $username, $password, $database);

if (isset($_GET['editeurId'])) {
    $editeurId = $_GET['editeurId'];

    // Écrivez votre requête pour récupérer les collections en fonction de l'éditeurId (utilisation de la requête préparée)
    $queryCollections = "SELECT id, nom FROM collection WHERE idediteur = ?";
    $queryLivresHorsCollection = "SELECT livre.id AS id, livre.nom AS nom, auteur.nom AS auteur, editeur.nom AS editeur, genre.nom AS genre, langue.nom AS langue, livre.infos AS infos 
    FROM livre 
    JOIN auteur ON livre.idauteur = auteur.id 
    JOIN editeur ON livre.idediteur = editeur.id 
    JOIN genre ON livre.idgenre = genre.id 
    JOIN langue ON livre.idlangue = langue.id 
    WHERE idediteur = $editeurId AND idcollection = 1";

    $stmtCollections = $conn->prepare($queryCollections);
    $stmtCollections->bind_param("i", $editeurId);
    $stmtCollections->execute();
    $resultCollections = $stmtCollections->get_result();
    echo '<div class="container">';
    if ($resultCollections && $resultCollections->num_rows > 0) {
        while ($rowCollection = $resultCollections->fetch_assoc()) {
            $collectionId = $rowCollection['id'];
            $collectionName = $rowCollection['nom'];
             
            echo '<div class="book">';
            echo '<div class="title-bar">';
            echo "<a href='#' class='collection-link' data-collection-id='$collectionId'>" . htmlspecialchars($collectionName) . "</a>";

            echo '</div>';
            echo '</div>';
            
        }
    } else {
        echo "Aucune collection trouvée pour cet éditeur.";
    }
    echo '</div>';
    $stmtCollections->close();
} else {
    echo "Erreur : Pas d'ID d'éditeur fourni.";
}


$resultLivresHorsCollection = $conn->query($queryLivresHorsCollection);

if ($resultLivresHorsCollection && $resultLivresHorsCollection->num_rows > 0) {
    echo '<div class="book">';
    echo '<div class="title-bar">';
    echo "<a href='#' class='collection-link' data-collection-id='0'>Hors collection</a>";
    echo '</div>';
    while ($rowLivre = $resultLivresHorsCollection->fetch_assoc()) {
        // Affichez les détails du livre ici
    }
    echo '</div>';
} else {
    echo "Aucun livre hors collection trouvé pour cet éditeur.";
}

$conn->close();

?>
