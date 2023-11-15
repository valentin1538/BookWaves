<?php
  // Initialiser la session
  session_start();
  
  // Détruire la session.
  session_destroy();

// Redirige vers la même page ou une autre page après la déconnexion
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : './login.php';

if (strpos($_SERVER['HTTP_REFERER'], '/playlist.php') !== false) {
  $redirect = '../index.php'; // Modifie le chemin selon ta structure de fichiers
}

header("Location: " . $redirect);
exit();

?>