<?php
// SOUS PROJET HUGO DAVION 

  // Initialiser la session
  session_start();
  
  // Détruire la session.
  session_destroy();

// Redirige vers la même page ou une autre page après la déconnexion
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : './login.php';

header("Location: " . $redirect);
exit();

?>