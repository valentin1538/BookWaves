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

session_start();
// Vérifiez si le paramètre genreId est présent dans la requête GET
if (isset($_GET['genreId'])) {
    $genreId = $_GET['genreId'];

    // Requête pour récupérer le nom du genre sélectionné
    $queryGenreName = "SELECT nom FROM genre WHERE id = $genreId";
    $resultGenreName = $conn->query($queryGenreName);

    // Récupérez l'id de la personne connectée depuis la session
    $idPersonneConnectee = $_SESSION["id"];

    if ($resultGenreName && $resultGenreName->num_rows > 0) {
        $rowGenreName = $resultGenreName->fetch_assoc();
        $genreName = $rowGenreName['nom'];

        // Requête pour récupérer les livres du genre spécifié
        $queryLivresGenre = "SELECT livreperso.id AS id, livreperso.nom AS nom, livreperso.lienfolder AS nomfichier, auteur.nom AS auteur, editeur.nom AS editeur, genre.nom AS genre, langue.nom AS langue
        FROM livreperso 
        JOIN auteur ON livreperso.idauteur = auteur.id 
        JOIN editeur ON livreperso.idediteur = editeur.id 
        JOIN genre ON livreperso.idgenre = genre.id 
        JOIN langue ON livreperso.idlangue = langue.id  
        WHERE idgenre = $genreId
        AND livreperso.idpersonne = $idPersonneConnectee";
        $resultLivresGenre = $conn->query($queryLivresGenre);

        if ($resultLivresGenre && $resultLivresGenre->num_rows > 0) {
            echo "<h3>$genreName</h3>";
            echo '<div class="container">';
            while ($rowLivre = $resultLivresGenre->fetch_assoc()) {
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
                echo '<a href="../pages_autres/visualiser.php?nomfichier=' . urlencode($livreNomFichier) . '"><i class="fa fa-eye"></i> Visualiser</a>';
                echo '</li>';
                echo '</ul>';
                echo '</div>';
                echo '</div>';
                echo '<button class="btn-info" onclick="showBookInfo(' . urlencode($livreId) . ')">Info</button>';
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