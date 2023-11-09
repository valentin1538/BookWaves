<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Se connecter</title>
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="home">
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<body>
<center>
<?php
require('Config.php');
session_start();
if (isset($_POST['username'])){
  $username = stripslashes($_REQUEST['username']);
  $username = mysqli_real_escape_string($conn, $username); 
  // récupérer le mot de passe et supprimer les antislashes ajoutés par le formulaire
  $password = stripslashes($_REQUEST['password']);
  $password = mysqli_real_escape_string($conn, $password);
  $query = "SELECT * FROM `users` WHERE username='$username' and password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $_SESSION['username'] = $username;
        // L'utilisateur est authentifié avec succès
        $row = $result->fetch_assoc();
        $_SESSION["admin"] = $row["admin"]; // Stocker le statut administrateur dans la session

        if ($row["admin"] == 1) {
            header("Location: admin.php"); // Redirigez vers la page de gestion des auteurs pour les administrateurs
        } else 
        {
            header("Location: index.php"); // Redirigez vers la page de gestion des livres pour les utilisateurs standard
        }
    } else {
        $message_erreur = "Adresse e-mail ou mot de passe incorrect";
    }
  
}
?>

<form class="box" action="" method="post" name="login">
<p>Connexion<p>
<input type="text" class="box-input" name="username" placeholder="Nom d'utilisateur">
<input type="password" class="box-input" name="password" placeholder="Mot de passe">
<input type="submit" value="Connexion " name="submit" class="box-button">
<p class="box-register">Vous pouvez aussi vous <a href="register.php"> inscrire</a></p>

<?php if (! empty($message)) { ?>
    <p class="errorMessage"><?php echo $message; ?></p>
<?php } ?>
</form>

<center/>
</body>
</html>