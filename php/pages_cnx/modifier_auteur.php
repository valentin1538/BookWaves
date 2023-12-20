<!-- SOUS PROJET HUGO DAVION -->

<?php
// Connexion à la base de données
$servername = "localhost"; // Remplacez par le nom de votre serveur de base de données
$username = "root"; // Remplacez par votre nom d'utilisateur de base de données
$password = ""; // Remplacez par votre mot de passe de base de données
$database = "Biblio"; // Remplacez par le nom de votre base de données

// Créez la connexion à la base de données
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["modifier_auteur"])) {
        $auteur_id = $_POST["auteur_id"];
        $nouveau_nom = $_POST["nouveau_nom"];

        $sql = "UPDATE auteur SET nom='$nouveau_nom' WHERE id=$auteur_id";

        if ($conn->query($sql) === TRUE) {
            header("Location: gestion_auteurs.php"); // Redirection après modification
        } else {
            echo "Erreur lors de la modification de l'auteur : " . $conn->error;
        }
    }
}

if (isset($_GET["id"])) {
    $auteur_id = $_GET["id"];
    $sql = "SELECT * FROM auteur WHERE id=$auteur_id";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Auteur non trouvé.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier un Auteur</title>
</head>
<body>
    <h1>Modifier un Auteur</h1>

    <form method="POST" action="modifier_auteur.php">
        <input type="hidden" name="auteur_id" value="<?php echo $auteur_id; ?>">
        <label for="nouveau_nom">Nouveau Nom de l'Auteur :</label>
        <input type="text" name="nouveau_nom" value="<?php echo $row["nom"]; ?>" required>
        <input type="submit" name="modifier_auteur" value="Modifier Auteur">
    </form>

    <?php
    $conn->close();
    ?>
</body>
</html>