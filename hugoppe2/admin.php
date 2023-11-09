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

session_start();
  // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
  if(!isset($_SESSION["username"])){
    header("Location: login.php");
    exit(); 
  }
  //On vérifie si l'utilisateur qui tente de se connecter est un administrateur
  if(($_SESSION["admin"])!=1){
    header("Location: login.php");
    exit(); 
  }

// Récupération de l'ID de l'auteur à modifier depuis l'URL
if (isset($_GET["edit"])) {
    $auteur_id = $_GET["edit"];
    $sql = "SELECT * FROM auteur WHERE id=$auteur_id";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Auteur non trouvé.";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ajout d'un nouvel auteur
    if (isset($_POST["ajouter_auteur"])) {
        $nom_auteur = $_POST["nom_auteur"];
        $sql = "INSERT INTO auteur (nom) VALUES ('$nom_auteur')";
        if ($conn->query($sql) === TRUE) {
            header("Location: gestion_auteurs.php"); // Redirection après ajout
        } else {
            echo "Erreur lors de l'ajout de l'auteur : " . $conn->error;
        }
    }

    // Modification de l'auteur existant
    if (isset($_POST["modifier_auteur"])) {
        $nouveau_nom = $_POST["nouveau_nom"];
        $sql = "UPDATE auteur SET nom='$nouveau_nom' WHERE id=$auteur_id";
        if ($conn->query($sql) === TRUE) {
            header("Location: gestion_auteurs.php"); // Redirection après modification
        } else {
            echo "Erreur lors de la modification de l'auteur : " . $conn->error;
        }
    }
}

// Suppression de l'auteur
if (isset($_GET["delete"])) {
    $auteur_id = $_GET["delete"];
    $sql = "DELETE FROM auteur WHERE id=$auteur_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: gestion_auteurs.php"); // Redirection après suppression
    } else {
        echo "Erreur lors de la suppression de l'auteur : " . $conn->error;
    }
}

// Affichage de la liste des auteurs
$sql = "SELECT * FROM auteur";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Auteurs</title>
    <a href="logout.php">Déconnexion</a>
    <a href="index.php">Accueil</a>
</head>
<body>
    <h1>Gestion des Auteurs</h1>
    <p>Bienvenue <?php echo $_SESSION['username']; ?>!<p>
    <p>C'est votre tableau de bord.</p>

    <!-- Formulaire d'ajout d'auteur -->
    <h2>Ajouter un Auteur</h2>
    <form method="POST" action="gestion_auteurs.php">
        <label for="nom_auteur">Nom de l'Auteur :</label>
        <input type="text" name="nom_auteur" required>
        <input type="submit" name="ajouter_auteur" value="Ajouter Auteur">
    </form>
<br><br>
    <!-- Liste des auteurs -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nom de l'Auteur</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["nom"] . "</td>";
                echo '<td><a href="modifier_auteur.php?id=' . $row["id"] . '">Modifier</a> | <a href="gestion_auteurs.php?delete=' . $row["id"] . '">Supprimer</a></td>';
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Aucun auteur trouvé.</td></tr>";
        }
        ?>
    </table>

    <?php
    // Formulaire de modification d'auteur
    if (isset($_GET["edit"])) {
        echo '<h2>Modifier un Auteur</h2>';
        echo '<form method="POST" action="gestion_auteurs.php?edit=' . $auteur_id . '">';
        echo '<label for="nouveau_nom">Nouveau Nom de l\'Auteur :</label>';
        echo '<input type="text" name="nouveau_nom" value="' . $row["nom"] . '" required>';
        echo '<input type="submit" name="modifier_auteur" value="Modifier Auteur">';
        echo '</form>';
    }

    $conn->close();
    ?>
</body>
</html>