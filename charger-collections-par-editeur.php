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
            echo "<a href='#'>$collectionName</a>";
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

$conn->close();
?>
