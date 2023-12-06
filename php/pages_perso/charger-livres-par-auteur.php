<?php

session_start();
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
if (isset($_GET['auteurId'])) {
    $auteurId = $_GET['auteurId'];
    $Category = $_GET['category'];


    // Requête pour récupérer le nom du genre sélectionné
    $queryAuteurName = "SELECT nom FROM auteur WHERE id = $auteurId";
    $resultAuteurName = $conn->query($queryAuteurName);

    // Récupérez l'id de la personne connectée depuis la session
    $idPersonneConnectee = $_SESSION["id"];

    if ($resultAuteurName && $resultAuteurName->num_rows > 0) {
        $rowAuteurName = $resultAuteurName->fetch_assoc();
        $auteurName = $rowAuteurName['nom'];

        // Requête pour récupérer les livres du genre spécifié
        $queryLivresAuteur = "SELECT livreperso.id AS id, livreperso.lienfolder AS nomfichier, livreperso.nom AS nom, auteur.nom AS auteur, editeur.nom AS editeur, genre.nom AS genre, langue.nom AS langue 
        FROM livreperso 
        JOIN auteur ON livreperso.idauteur = auteur.id 
        JOIN editeur ON livreperso.idediteur = editeur.id 
        JOIN genre ON livreperso.idgenre = genre.id 
        JOIN langue ON livreperso.idlangue = langue.id  
        WHERE idauteur = $auteurId
        AND livreperso.idpersonne = $idPersonneConnectee";

        $resultLivresAuteur = $conn->query($queryLivresAuteur);

        if ($resultLivresAuteur && $resultLivresAuteur->num_rows > 0) {
            echo "<h3>$auteurName</h3>";
            echo '<div class="container">';
            while ($rowLivre = $resultLivresAuteur->fetch_assoc()) {
                $livreId = $rowLivre['id'];
                $livreNom = $rowLivre['nom'];
                $livreAuteur = $rowLivre['auteur'];
                $livreEditeur = $rowLivre['editeur'];
                $livreGenre = $rowLivre['genre'];
                $livreLangues = $rowLivre['langue'];
                $livreNomFichier = $rowLivre['nomfichier'];

                echo '<div class="book">';
                echo '<div class="title-bar">';
                echo '<h2>' . (isset($livreNom) ? htmlspecialchars($livreNom) : 'Inconnu') . '</h2>';
                echo '<div id="header_ajout_livre_bar" class="dropdown bars">';
                echo '<a data-toggle="dropdown" class="dropdown-toggle" href="#">';
                echo '<i class="fa-solid fa-bars"></i>';
                echo '</a>';
                echo '<ul class="dropdown-menu extended notification">';
                echo '<div class="notify-arrow notify-arrow-green"></div>';
                echo '<li>';
                echo '<a href="../pages_autres/visualiser.php?Category=' . urlencode($Category) . '&nomfichier=' . urlencode($livreNomFichier) . '"><i class="fa fa-eye"></i> Visualiser</a>';
                echo '</li>';
                echo '</ul>';
                echo '</div>';
                echo '</div>';
                echo '<button class="btn-info" onclick="showBookInfo(' . urlencode($livreId) . ')">Info</button>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo "Aucun livre trouvé pour cet auteur.";
        }
    } else {
        echo "Nom de l'auteur non trouvé.";
    }
} else {
    echo "Paramètre auteurId manquant.";
}

$conn->close();
?>