<?php
// SOUS PROJET HUGO DAVION 

// Connexion à la base de données
$servername = "localhost"; // Remplacez par le nom de votre serveur de base de données
$username = "root"; // Remplacez par votre nom d'utilisateur de base de données
$password = ""; // Remplacez par votre mot de passe de base de données
$database = "Biblio"; // Remplacez par le nom de votre base de données

// Créez la connexion à la base de données
$conn = new mysqli($servername, $username, $password, $database);

$userQuery = "SELECT id, username FROM users";
$userResult = $conn->query($userQuery);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}
 // Initialiser la session
  session_start();
  // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
  if(!isset($_SESSION["username"])){
    header("Location: ../pages_cnx/login.php");
    exit(); 
  }
  if(($_SESSION["idprofil"])!=4){
    header("Location: ../pages_cnx/login.php");
    exit(); 
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookWaves / Gestion</title>
    <!-- Bootstrap core CSS -->
    <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--external css-->
    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="../css/style.css" rel="stylesheet">
  <link href="../css/style-responsive.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/cf0cc41982.js" crossorigin="anonymous"></script>
</head>

<body>
  <section id="container">
      <!-- **********************************************************************************************************************************************************
          TOP BAR CONTENT & NOTIFICATIONS
          *********************************************************************************************************************************************************** -->
      <!--header start-->
      <header class="header black-bg">
        <!--logo start-->
        <a href="../index.php" class="logo"><b><span>BOOK WAVES <?php echo isset($_SESSION['username']) ? ' / ' . $_SESSION['username'] : ''; ?></span></b></a>
        <!--logo end-->
        <h2 style="color: white;">GESTION</h2>
        <div class="top-menu">
          <ul class="nav pull-right top-menu">
          <?php if (isset($_SESSION['username'])): ?>
            <!-- Utilisateur connecté -->
            <li><a class="logout" href="./pages_cnx/logout.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Déconnexion</a></li>
          <?php else: ?>
            <!-- Utilisateur non connecté -->
            <li><a class="logout" href="./pages_cnx/login.php">Se Connecter</a></li>
          <?php endif; ?>
          </ul>
        </div>
      </header>
      <!--header end-->
      <!-- **********************************************************************************************************************************************************
          MAIN SIDEBAR MENU
          *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <aside>
        <div id="sidebar" class="nav-collapse">
            <!-- sidebar menu start-->
            <ul class="sidebar-menu" id="nav-accordion">
                <li class="Formats">
                    <a href="#" id="biblioCommuneLink" class="menu-link">
                        <i class="fa fa-book"></i>
                        <span>Bibliothèque Commune</span>
                    </a>
                    <ul id="menuDeroulantCommun" class="menu-deroulant-commune">
                        <li class="auteur">
                            <a href="./index_admin.php">
                                <i class="fa fa-user-tie"></i>
                                <span>Livres</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="./gestion_users" class="active">
                        <i class="fa fa-users"></i>
                        <span>Gestion Utilisateurs</span>
                    </a>
                </li>
            </ul>
            <!-- sidebar menu end-->
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var menuLinks = document.querySelectorAll(".menu-link");

                menuLinks.forEach(function (link) {
                    link.addEventListener("click", function (event) {
                        event.preventDefault();
                        var menu = this.nextElementSibling;
                        menu.style.display = (menu.style.display === "none" || menu.style.display === "") ? "block" : "none";
                    });
                });
            });
        </script>
    </aside>
      <!--sidebar end-->

      <section id="main-content">
        <section class="wrapper">
          <div class="row">
            <div class="main-chart">
              <!--CUSTOM CHART START -->
              <div class="border-head">
                <h3>UTILISATEURS</h3>
                <div class ="container-ges" id="form-list">
                    <?php
                        require('../pages_cnx/config.php');

                        if (isset($_REQUEST['username'], $_REQUEST['email'], $_REQUEST['password'], $_REQUEST['idProfil'])) {
                            // récupérer le nom d'utilisateur et supprimer les antislashes ajoutés par le formulaire
                            $username = stripslashes($_REQUEST['username']);
                            $username = mysqli_real_escape_string($conn, $username);

                            // récupérer l'email et supprimer les antislashes ajoutés par le formulaire
                            $email = stripslashes($_REQUEST['email']);
                            $email = mysqli_real_escape_string($conn, $email);

                            // récupérer le mot de passe et supprimer les antislashes ajoutés par le formulaire
                            $password = stripslashes($_REQUEST['password']);
                            $password = mysqli_real_escape_string($conn, $password);

                            // récupérer le profil
                            $idProfil = $_REQUEST['idProfil'];

                            $zero = 1;

                            // Vérifier si le nom d'utilisateur ou l'email existe déjà
                            $check_query = "SELECT * FROM `users` WHERE username = '$username' OR email = '$email'";
                            $check_result = mysqli_query($conn, $check_query);

                            if (mysqli_num_rows($check_result) > 0) {
                                // L'utilisateur ou l'email existe déjà, afficher un message d'erreur
                            
                    
                            } else {
                                // Les informations ne sont pas en double, procéder à l'insertion dans la base de données
                                $query = "INSERT into `users` (username, email, password, idprofil)
                                        VALUES ('$username', '$email', '$password', '$idProfil')";

                                // Exécuter la requête sur la base de données
                                $res = mysqli_query($conn, $query);

                                if ($res) {
                                    echo "<div id='containerh'>
                                        <form class='box' action='' method='post' name='login'>
                                            <h3>Vous avez ajouté cet utilisateur avec succès.</h3>
                                            <h3>Cliquez ici pour revenir au<a href='indexad.php' style='color:skyblue' > menu</a></h3>
                                        </form>
                                        </div>";
                                } 
                            }
                        } else {
                            // Reste du code pour le formulaire d'inscription
                            // ...

                        ?>
                    </div>

                    <div class="ges-item">
                        <form class="box" action="" method="post" name="deleteUserForm">
                            
                            <h1 style='color:#4ecdc4'> Ajout d'utilisateur </h1> 
                            <input type="text" class="box-input" name="username" placeholder="Nom d'utilisateur" required />
                            <input type="text" class="box-input" name="email" placeholder="Email" required />
                            <input type="password" class="box-input" name="password" placeholder="Mot de passe" required />

                            <!-- Ajout du menu déroulant pour choisir le profil -->
                            <select name="idProfil">
                                <option value="1">Connecté</option>
                                <option value="2">Modérateur</option>
                                <option value="3">DBA</option>
                                <option value="4">Administrateur</option>
                            </select>

                            <input type="submit" name="submit" value="Confirmer" style='color:black' class="box-button" />
                        </form>
                        <?php } ?>
                    </div> 

                    <div class="ges-item">
                            <form class="box" action="" method="post" name="deleteUserForm">
                                <h1 style='color:red'> Suppression d'utilisateur </h1> 
                                <!-- Liste déroulante pour sélectionner l'utilisateur à supprimer -->
                                <select name="userToDelete">
                                    <?php
                                    // Afficher la liste des utilisateurs
                                    while ($row = $userResult->fetch_assoc()) {
                                        echo "<option value=\"{$row['id']}\">{$row['username']}</option>";
                                    }
                                    ?>
                                </select>

                                <!-- Bouton de confirmation pour supprimer l'utilisateur -->
                                <input type="submit" name="deleteUserSubmit" value="Supprimer" style='color:black' class="box-button" />
                            </form>
                    </div>

                    <!-- ... (Votre code existant) -->

                    <?php
                    // Traitement de la suppression d'utilisateur
                    if (isset($_POST['deleteUserSubmit'])) {
                        $userIdToDelete = $_POST['userToDelete'];

                        // Requête pour supprimer l'utilisateur sélectionné
                        $deleteUserQuery = "DELETE FROM users WHERE id = '$userIdToDelete'";
                        $deleteUserResult = $conn->query($deleteUserQuery);

                        if ($deleteUserResult) {
                            $userQuery = "SELECT id, username FROM users";
                            $userResult = $conn->query($userQuery);
                        }    
                    }
                    ?>

                      
                    <!-- Ajouter une section pour la modification d'utilisateur -->

                    <div  class="ges-item">
                        <form class="box" action="" method="post" name="modifyUserForm">
                            <h1 style='color:orange'> Modification d'utilisateur </h1> 
                            <!-- Liste déroulante pour sélectionner l'utilisateur à modifier -->
                            <select name="userToModify">
                                <?php
                                // Afficher la liste des utilisateurs
                                $userResult = $conn->query($userQuery); // Réexécutez la requête pour obtenir une liste mise à jour
                                while ($row = $userResult->fetch_assoc()) {
                                    echo "<option value=\"{$row['id']}\">{$row['username']}</option>";
                                }
                                ?>
                            </select>

                            <!-- Champ pour entrer le nouveau nom d'utilisateur -->
                            <input type="text" class="box-input" name="newUsername" placeholder="Nouveau nom d'utilisateur" required />

                            <!-- Champ pour entrer le nouvel email -->
                            <input type="text" class="box-input" name="newEmail" placeholder="Nouvel email" required />

                            <!-- Champ pour entrer le nouveau mot de passe -->
                            <input type="password" class="box-input" name="newPassword" placeholder="Nouveau mot de passe" required />

                            <!-- Liste déroulante pour sélectionner le nouveau type de profil -->
                            <select name="newProfileType">
                                <option value="1">Connecté</option>
                                <option value="2">Modérateur</option>
                                <option value="3">DBA</option>
                                <option value="4">Administrateur</option>
                            </select>

                            <!-- Bouton de confirmation pour modifier l'utilisateur -->
                            <input type="submit" name="modifyUserSubmit" value="Modifier" style='color:black' class="box-button" />
                        </form>
                    </div>

                    <?php
                    // Traitement de la modification d'utilisateur
                    if (isset($_POST['modifyUserSubmit'])) {
                        $userIdToModify = $_POST['userToModify'];
                        $newUsername = mysqli_real_escape_string($conn, $_POST['newUsername']);
                        $newEmail = mysqli_real_escape_string($conn, $_POST['newEmail']);
                        $newPassword = mysqli_real_escape_string($conn, $_POST['newPassword']);
                        $newProfileType = $_POST['newProfileType']; // Nouveau type de profil

                        // Requête pour mettre à jour l'utilisateur sélectionné
                        $modifyUserQuery = "UPDATE users SET username='$newUsername', email='$newEmail', password='$newPassword', idprofil='$newProfileType' WHERE id = '$userIdToModify'";
                        $modifyUserResult = $conn->query($modifyUserQuery);

                        if ($modifyUserResult) {
                            // Mettez à jour la liste des utilisateurs après la modification
                            $userQuery = "SELECT id, username FROM users";
                            $userResult = $conn->query($userQuery);
                        }    
                    }
                    ?>

                </div>
              </div>
              <!--custom chart end-->
            </div>
            <!-- /col-lg-3 -->
          </div>
          <!-- /row -->
        </section>
      </section>
      <!--main content end-->




</section>


    <!-- js placed at the end of the document so the pages load faster -->
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="lib/jquery.dcjqaccordion.2.7.js"></script>
    <script src="lib/jquery.scrollTo.min.js"></script>
    <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="lib/jquery.sparkline.js"></script>
    <!--common script for all pages-->
    <script src="lib/common-scripts.js"></script>
    <script type="text/javascript" src="lib/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="lib/gritter-conf.js"></script>
    <!--script for this page-->
    <script src="lib/sparkline-chart.js"></script>
    <script src="lib/zabuto_calendar.js"></script>
</body>
</html>