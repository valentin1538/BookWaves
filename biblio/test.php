<?php
// Paramètres de connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "";
$baseDeDonnées = "Biblio";

// Connexion à la base de données
$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnées);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}

// Récupérer les genres depuis la base de données
$genres = array();
$resultatGenres = $connexion->query("SELECT id, nom FROM genre");
if ($resultatGenres->num_rows > 0) {
    while ($row = $resultatGenres->fetch_assoc()) {
        $genres[$row['id']] = $row['nom'];
    }
}

// Genre sélectionné (par défaut, rien n'est sélectionné)
$genreSelectionne = "";

// Vérifie si un genre a été sélectionné et le stocke dans $genreSelectionne
if (isset($_POST['genre'])) {
    $genreSelectionne = $_POST['genre'];
}

// Reste du code
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Bibliothèque PHP</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h1>Ma Bibliothèque PHP</h1>
    </header>
    <div class="content">
        <h2>Contenu de la page</h2>
        <p>Bienvenue sur ma page web stylée.</p>
        <p>La date actuelle est : <?php echo date("d/m/Y"); ?></p>

        <form method="post">
            <label for="genre">Sélectionnez un genre :</label>
            <select name="genre" id="genre" onchange="this.form.submit()">
                <?php
                foreach ($genres as $id => $nom) {
                    $selected = ($id == $genreSelectionne) ? 'selected' : '';
                    echo '<option value="' . $id . '" ' . $selected . '>' . $nom . '</option>';
                }
                ?>
            </select>
        </form>

        <form method="post">
            <label for="livre">Sélectionnez un livre :</label>
            <select name="livre" id="livre">
                <?php
                if (isset($_POST['genre'])) {
                    $genre = $_POST['genre'];
                    // Récupérer les livres en fonction du genre sélectionné depuis la base de données
                    $requeteLivres = "SELECT nom FROM livre WHERE idgenre = " . $connexion->real_escape_string($genre);
                    $resultatLivres = $connexion->query($requeteLivres);
                    if ($resultatLivres->num_rows > 0) {
                        while ($row = $resultatLivres->fetch_assoc()) {
                            echo '<option value="' . $row['nom'] . '">' . $row['nom'] . '</option>';
                        }
                    }
                }
                ?>
            </select>
        </form>
    </div>
</body>
</html>

<?php
// Fermez la connexion à la base de données à la fin du script
$connexion->close();
?>
