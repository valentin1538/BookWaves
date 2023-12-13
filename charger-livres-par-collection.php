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

// Vérifiez si le paramètre collectionId est présent dans la requête GET
if (isset($_GET['collectionId'])) {
    $collectionId = $_GET['collectionId'];

    // Requête pour récupérer le nom du collection sélectionné (utilisation de la requête préparée)
    $querycollectionName = "SELECT nom FROM collection WHERE id = ?";
    $stmtcollectionName = $conn->prepare($querycollectionName);
    $stmtcollectionName->bind_param("i", $collectionId);
    $stmtcollectionName->execute();
    $resultcollectionName = $stmtcollectionName->get_result();

    if ($resultcollectionName && $resultcollectionName->num_rows > 0) {
        $rowcollectionName = $resultcollectionName->fetch_assoc();
        $collectionName = $rowcollectionName['nom'];

        // Requête pour récupérer les livres du collection spécifié (utilisation de la requête préparée)
        $queryLivresCollection = "SELECT livre.id AS id, livre.nom AS nom, auteur.nom AS auteur, collection.nom AS editeur, genre.nom AS genre, langue.nom AS langue, livre.infos AS infos 
        FROM livre 
        JOIN auteur ON livre.idauteur = auteur.id 
        JOIN collection ON livre.idcollection = collection.id 
        JOIN genre ON livre.idgenre = genre.id 
        JOIN langue ON livre.idlangue = langue.id  
        WHERE livre.idcollection = ?";
        
        
        $stmtLivrescollection = $conn->prepare($queryLivrescollection);
        $stmtLivrescollection->bind_param("i", $collectionId);
        $stmtLivrescollection->execute();
        $resultLivrescollection = $stmtLivrescollection->get_result();

        if ($resultLivrescollection && $resultLivrescollection->num_rows > 0) {
            echo '<div class="container">';
            echo "<h3>$collectionName</h3>";
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
                echo "<h2>$livreNom</h2>";
                echo "<p><strong>Auteur :</strong> $livreAuteur</p>";
                echo "<p><strong>Éditeur :</strong> $livreEditeur</p>";
                echo "<p><strong>Genre :</strong> $livreGenre</p>";
                echo "<p><strong>Langue :</strong> $livreLangues</p>";
                echo '</div>';
                echo '</div>';
            }
            
            echo '</div>';
        } else {
            echo "Aucun livre trouvé pour ce editeur.";
        }
    } else {
        echo "Nom du editeur non trouvé.";
    }

    $stmtcollectionName->close();
    $stmtLivrescollection->close();
} else {
    echo "Paramètre editeurId manquant.";
}

$conn->close();
?>
