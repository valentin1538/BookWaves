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

// Vérifiez si le formulaire de connexion a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = $_POST["mail"];
    $motdepasse = $_POST["motdepasse"];

    // Requête pour vérifier les informations de connexion
    $sql = "SELECT * FROM utilisateur WHERE mail = '$mail' AND motdepasse = '$motdepasse'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // L'utilisateur est authentifié avec succès
        $row = $result->fetch_assoc();
        session_start();
        $_SESSION["utilisateur_authentifie"] = true;
        $_SESSION["is_admin"] = $row["isadmin"]; // Stocker le statut administrateur dans la session

        if ($row["isadmin"] == 1) {
            header("Location: gestion_auteurs.php"); // Redirigez vers la page de gestion des auteurs pour les administrateurs
        } else {
            header("Location: gestion_livres.php"); // Redirigez vers la page de gestion des livres pour les utilisateurs standard
        }
    } else {
        $message_erreur = "Adresse e-mail ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>

    <form method="POST" action="index.php">
        <label for="mail">Adresse e-mail :</label>
        <input type="text" name="mail" required><br>

        <label for="motdepasse">Mot de passe :</label>
        <input type="password" name="motdepasse" required><br>

        <input type="submit" name="connexion" value="Se connecter">
    </form>

    <?php
    if (isset($message_erreur)) {
        echo '<p style="color: red;">' . $message_erreur . '</p>';
    }
    ?>

</body>
</html>