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

// Vérifie si un genre a été sélectionné et le stocke dans $genreSelectionne afin de la garder lors du raffraichissement de la page 
if (isset($_POST['genre'])) {
    $genreSelectionne = $_POST['genre'];
}

// Ajout d'un nouveau genre
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nouveau_genre"])) {
    $nouveauGenre = trim($_POST["nouveau_genre"]);

    // Vérifier si le champ est vide
    if (empty($nouveauGenre)) {
        $messageErreur = "Veuillez saisir un nom de genre."; // Message champs vide
    } else {
        // Vérifier si le genre existe deja
        $requeteVerification_ajout = "SELECT id FROM genre WHERE nom = '$nouveauGenre'";
        $requeteVerification_ajout = $connexion->query($requeteVerification_ajout);

        if ($requeteVerification_ajout->num_rows > 0) {
            $messageErreur = "Le genre '$nouveauGenre' existe déjà."; // Message existant
        } else {
            // Ajouter le nouveau genre à la base de données
            $requeteAjout = "INSERT INTO genre (nom) VALUES ('$nouveauGenre')"; 
            if ($connexion->query($requeteAjout)) {
                $messageSucces = "Le genre '$nouveauGenre' a été ajouté avec succès."; // Message Succés
            } else {
                $messageErreur = "Une erreur est survenue lors de l'ajout du genre."; // Message echec
            }
        }
    }
}


// Suppression d'un genre
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_genre"])) {
    $deleteGenre = trim($_POST["delete_genre"]);

    // Vérifier si le champ est vide
    if (empty($deleteGenre)) {
        $messageErreur = "Veuillez saisir un nom de genre."; // Message champs vide
    }
    else {
        // Vérifier si le genre existe bien
        $requeteVerification_delete = "SELECT id FROM genre WHERE nom = '$deleteGenre'";
        $requeteVerification_delete = $connexion->query($requeteVerification_delete);

        if ($requeteVerification_delete->num_rows > 0) {
            $requetedelete = "DELETE FROM genre WHERE nom = '$deleteGenre'"; 
                if ($connexion->query($requetedelete)) {
                    $messageSucces = "Le genre '$deleteGenre' a été Supprimer avec succès."; }// Message Succés
                else {
                    $messageErreur = "Une erreur est survenue lors de la suppression du genre.";} // Message echec
        } 
            else {
                $messageErreur = "Le genre '$deleteGenre' existe pas.";} // Message existant                               
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Bibliothèque PHP</title>
    <!--Lien vers le style.css de la page--> 
    <link rel="stylesheet" type="text/css" href="style.css"> 
</head>
<body>
    <header>
        <h1>Ma Bibliothèque PHP</h1>
    </header>
    <div class="content">
        <h2>Contenu de la page</h2>
        <p>Bienvenue sur ma Biblioteque en PHP.</p>
        <p>La date actuelle est : <?php echo date("d/m/Y"); ?></p>

        <form method="post">
            <label for="genre">Sélectionnez un genre :</label>
            <select name="genre" id="genre" onchange="this.form.submit()"> <!--Soumet le choix que l'utilisateur a selectionner-->
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

        <form method="post">
            <label for="nouveau_genre">Ajouter un nouveau genre :</label>
            <input type="text" name="nouveau_genre" id="nouveau_genre"> <!--Input pour rentrer le nom du nouveau genre -->
            <button type="submit">Ajouter</button>
        </form>

        <form method="post">
            <label for="delete_genre">Supprimer un genre :</label>
            <input type="text" name="delete_genre" id="delete_genre"> <!--Input pour rentrer le nom du nouveau genre -->
            <button type="submit">Supprimer</button>
        </form>

        <?php
            if (isset($messageErreur)) {
                echo '<p style="color: red;">' . $messageErreur . '</p>';} // Style pour le message d'erreur
            elseif (isset($messageSucces)) {
                echo '<p style="color: green;">' . $messageSucces . '</p>';} // Style pour le message de succes 
        ?>
    </div>
</body>
</html>

<?php
// Ferme la connexion à la base de données
$connexion->close();
?>
