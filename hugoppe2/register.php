<!DOCTYPE html>
<html lang="fr">
<head>
	<title>S'inscrire</title>
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="cnx.css">
</head>
<body class="home">
<br><br><br>
<body>
<center>
<?php
require('config.php');
if (isset($_REQUEST['username'], $_REQUEST['email'], $_REQUEST['password'])){
  // récupérer le nom d'utilisateur et supprimer les antislashes ajoutés par le formulaire
  $username = stripslashes($_REQUEST['username']);
  $username = mysqli_real_escape_string($conn, $username); 
  // récupérer l'email et supprimer les antislashes ajoutés par le formulaire
  $email = stripslashes($_REQUEST['email']);
  $email = mysqli_real_escape_string($conn, $email);
  // récupérer le mot de passe et supprimer les antislashes ajoutés par le formulaire
  $password = stripslashes($_REQUEST['password']);
  $password = mysqli_real_escape_string($conn, $password);
  $zero = 0 ;
  //requéte SQL + mot de passe crypté
    $query = "INSERT into `users` (username, email, password, admin)
              VALUES ('$username', '$email', '$password','$zero')";
  // Exécuter la requête sur la base de données
    $res = mysqli_query($conn, $query);
    if($res){
       echo "<div id='container'>
       
<form class='box' action='' method='post' name='login'>
             <h3>Vous êtes inscrit avec succès.</h3>
             <h3>Cliquez ici pour vous <a href='login.php' style='color:skyblue' >connecter</a></h3>
       </div>";
    }
}else{
?>
</center>
<div id="container">
<form class="box" action="" method="post" name="login">
<h1>Inscription<h1>
<h2>Nom d'utilisateur</h2>
  <input type="text" class="box-input" name="username" placeholder="Nom d'utilisateur" required />
  <h2>Email</h2>  
  <input type="text" class="box-input" name="email" placeholder="Email" required />
  <h2>Mot de passe</h2> 
  <input type="password" class="box-input" name="password" placeholder="Mot de passe" required />
    <input type="submit" name="submit" value="Confirmer" style='color:black' class="box-button" />
    <h3 class="box-register">Déjà inscrit? <a href="login.php" style='color:skyblue'>Connectez-vous ici</a></h3>
</form>
<?php } ?>


</html>