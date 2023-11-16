<!DOCTYPE html>
<html>
 <head>
 <meta charset="utf-8">	<title>Se connecter</title>
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="../css/cnx.css">
 </head>
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
        $_SESSION["id"] = $row["id"];
        $_SESSION["email"] = $row["email"];
        if ($row["admin"] == 1) {
            header("Location: admin.php"); // Redirigez vers la page de gestion des auteurs pour les administrateurs
        } else 
        {
            header("Location: ../index.php"); // Redirigez vers la page de gestion des livres pour les utilisateurs standard
        }
    } else {
        $message_erreur = "Adresse e-mail ou mot de passe incorrect";
    }
  
}
?>

</center>
<br><br><br>
<div id="container">
<form class="box" action="" method="post" name="login">
<h1>Connexion<h1>

<h2>Nom d'utilisateur</h2>
<input type="text" class="box-input" name="username" placeholder="Nom d'utilisateur">

<br>
<h2>Mot de passe</h2>
<input type="password" class="box-input" name="password" placeholder="Mot de passe">
<input type="submit" value="Confirmer " name="submit" style='color:Black' class="box-button">
<h3 class="box-register">Vous pouvez aussi vous <a href="register.php" style='color:skyblue'> inscrire</a></h3>

<?php if (! empty($message)) { ?>
    <p class="errorMessage"><?php echo $message; ?></p>
<?php } ?>
</form>
</div>

</html>