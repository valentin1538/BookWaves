<?php
// SOUS PROJET MATHEO ET BENJAMIN

// Connexion à la base de données (utilisez vos propres informations de connexion)
$servername = "localhost";
$username = "root";
$password = "";
$database = "Biblio";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Vérifiez si le paramètre editeurId est présent dans la requête GET
if (isset($_GET['editeurId'])) {
    $editeurId = $_GET['editeurId'];

    // Requête pour récupérer le nom du editeur sélectionné
    $queryediteurName = "SELECT nom FROM editeur WHERE id = $editeurId";
    $resultediteurName = $conn->query($queryediteurName);

    if ($resultediteurName && $resultediteurName->num_rows > 0) {
        $rowediteurName = $resultediteurName->fetch_assoc();
        $editeurName = $rowediteurName['nom'];

        // Requête pour récupérer les livres du editeur spécifié
        $queryLivresediteur = "SELECT livre.id AS id, livre.lienfolder AS nomfichier, livre.nom AS nom, auteur.nom AS auteur, editeur.nom AS editeur, genre.nom AS genre, langue.nom AS langue 
        FROM livre 
        JOIN auteur ON livre.idauteur = auteur.id 
        JOIN editeur ON livre.idediteur = editeur.id 
        JOIN genre ON livre.idgenre = genre.id 
        JOIN langue ON livre.idlangue = langue.id  WHERE idediteur = $editeurId ";
        $resultLivresediteur = $conn->query($queryLivresediteur);

        if ($resultLivresediteur && $resultLivresediteur->num_rows > 0) {
            echo "<h3>$editeurName</h3>";
            echo '<div class="container">';
            while ($rowLivre = $resultLivresediteur->fetch_assoc()) {
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
            echo "Aucun livre trouvé pour ce editeur.";
        }
    } else {
        echo "Nom du editeur non trouvé.";
    }
} else {
    echo "Paramètre editeurId manquant.";
}

$conn->close();
?>