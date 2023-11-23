<?php
ob_start();
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


  // Récupérer les genres depuis la base de données
  $genres = array();
  $resultatGenres = $conn->query("SELECT id, nom FROM genre");
  if ($resultatGenres->num_rows > 0) {
      while ($row = $resultatGenres->fetch_assoc()) {
          $genres[$row['id']] = $row['nom'];
      }
  }

// Genre sélectionné (par défaut rien n'est sélectionné)
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
      $requeteVerification_ajout = $conn->query($requeteVerification_ajout);

      if ($requeteVerification_ajout->num_rows > 0) {
          $messageErreur = "Le genre '$nouveauGenre' existe déjà."; // Message existant
      } else {
          // Ajouter le nouveau genre à la base de données
          $requeteAjout = "INSERT INTO genre (nom) VALUES ('$nouveauGenre')"; 
          if ($conn->query($requeteAjout)) {
              $messageSucces = "Le genre '$nouveauGenre' a été ajouté avec succès."; // Message Succés
          } else {
              $messageErreur = "Une erreur est survenue lors de l'ajout du genre."; // Message echec
          }
      }
  }
}


// Suppression d'un genre
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_genre"])) {
  $deleteGenreId = $_POST["delete_genre"];

  // Vérifier si le champ est vide
  if (empty($deleteGenreId)) {
      $messageErreur = "Veuillez sélectionner un genre à supprimer."; // Message champs vide
  }
  else {
      // Vérifier si le genre existe bien
      $requeteVerification_delete = "SELECT id FROM genre WHERE id = $deleteGenreId";
      $requeteVerification_delete = $conn->query($requeteVerification_delete);

      if ($requeteVerification_delete->num_rows > 0) {
          $requetedelete = "DELETE FROM genre WHERE id = $deleteGenreId";// Delete du genre
          if ($conn->query($requetedelete)) {
              $messageSucces = "Le genre a été supprimé avec succès."; }// Message Succés
          else {
              $messageErreur = "Une erreur est survenue lors de la suppression du genre.";} // Message echec
      } 
      else {
          $messageErreur = "Le genre sélectionné n'existe pas.";} // Message existant                               
  }
}


// Récupérer tous les livres depuis la base de données
$livresExistants = array();
$resultatLivresExistants = $conn->query("SELECT id, nom FROM livre");
if ($resultatLivresExistants->num_rows > 0) {
  while ($row = $resultatLivresExistants->fetch_assoc()) {
      $livresExistants[$row['id']] = $row['nom'];
  }
}

// Ajout d'un nouveau livre dans un genre
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter_livre"])) {
  $livreExistantsId = $_POST["livre_existants"]; // Récupérer l'ID du livre
  $genreDuLivre = $_POST["genre_du_livre_existants"];

  // Vérifier si les champs ne sont pas vides
  if (empty($livreExistantsId) || empty($genreDuLivre)) {
      $messageErreur = "Veuillez remplir tous les champs.";
  } else {
      // Récupérer le nom du livre à partir de l'ID
      $livreExistantsNom = $livresExistants[$livreExistantsId];
      
      // Vérifier si le livre existe déjà dans le genre
      $requeteVerification = "SELECT COUNT(*) AS nbLivres FROM livre WHERE nom = '$livreExistantsNom' AND idgenre = $genreDuLivre";
      $resultatVerification = $conn->query($requeteVerification);

      if ($resultatVerification && $resultatVerification->num_rows > 0) {
          $row = $resultatVerification->fetch_assoc();
          $nbLivres = $row['nbLivres'];

          if ($nbLivres > 0) {
              $messageErreur = "Le livre '$livreExistantsNom' est déjà associé à ce genre.";
          } else {
              // Mise à jour du genre du livre dans la base de données
              $requeteMiseAJourLivre = "UPDATE livre SET idgenre = $genreDuLivre WHERE nom = '$livreExistantsNom'";
              if ($conn->query($requeteMiseAJourLivre)) {
                  $messageSucces = "Le genre du livre '$livreExistantsNom' a été mis à jour avec succès.";
              } else {
                  $messageErreur = "Une erreur est survenue lors de la mise à jour du genre du livre.";
              }
          }
      }
  }
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
                <h3>Genre</h3>
                <div class ="container-ges" id="form-list">
                <?php
                        require('../pages_cnx/config.php');

                        if (isset($_REQUEST['nomGenre'])) {
                            // récupérer le nom du genre et supprimer les antislashes ajoutés par le formulaire
                            $nomGenre = stripslashes($_REQUEST['nomGenre']);
                            $nomGenre = mysqli_real_escape_string($conn, $nomGenre);

                            // Vérifier si l'éditeur existe déjà
                            $check_query = "SELECT * FROM `genre` WHERE nom = '$nomGenre'";
                            $check_result = mysqli_query($conn, $check_query);

                            if (mysqli_num_rows($check_result) > 0) {
                                // L'éditeur existe déjà, afficher un message d'erreur
                                echo "le Genre existe deja.";
                            } else {
                                // L'éditeur n'existe pas, procéder à l'insertion dans la base de données
                                $query = "INSERT INTO `genre` (nom) VALUES ('$nomGenre')";

                                // Exécuter la requête sur la base de données
                                $res = mysqli_query($conn, $query);

                                if ($res) {
                                    echo "<div id='containerh'>
                                            <form class='box' action='' method='post' name='login'>
                                                <h3>Vous avez ajouté ce genre avec succès.</h3>
                                            </form>
                                        </div>";
                                }
                            }
                        } else {
                            // Reste du code pour le formulaire d'ajout d'éditeur
                            // ...
                        }
                        ?>
                    </div>

                    <div class="ges-item">
                        <form class="box" action="" method="post" name="addGenreForm">

                            <h1 style='color:#4ecdc4'> Ajout d'un Genre </h1>
                            <input type="text" class="box-input" name="nomGenre" placeholder="Nom du genre" required />
                            <input type="submit" name="submitGenre" value="Confirmer" style='color:black' class="box-button" />
                        </form>
                    </div>

                    <div class="ges-item">
                            <form class="box" action="" method="post" name="deleteGenreForm">
                                <h1 style='color:red'> Suppression de Genre </h1> 
                                <!-- Liste déroulante pour sélectionner l'utilisateur à supprimer -->
                                <select name="genreToDelete">
                                    <?php
                                    // Afficher la liste des Genres
                                    $genreResult = $conn->query("SELECT id, nom FROM genre");
                                    while ($row = $genreResult->fetch_assoc()) {
                                        echo "<option value=\"{$row['id']}\">{$row['nom']}</option>";
                                    }
                                    ?>
                                </select>

                                <!-- Bouton de confirmation pour supprimer l'utilisateur -->
                                <input type="submit" name="deleteGenreSubmit" value="Supprimer" style='color:black' class="box-button" />
                            </form>
                    </div>

                    <?php
                    // Traitement de la suppression d'utilisateur
                    if (isset($_POST['deleteGenreSubmit'])) {
                        $genreIdToDelete = $_POST['genreToDelete'];

                        // Requête pour supprimer l'utilisateur sélectionné
                        $deleteGenreQuery = "DELETE FROM genre WHERE id = '$genreIdToDelete'";
                        $deleteGenreResult = $conn->query($deleteGenreQuery);

                        if ($deleteGenreResult) {
                           header("Location: {$_SERVER['PHP_SELF']}");
                        }    
                    }
                    ?>

                      
                    <div class="ges-item">
                        <form class="box" action="" method="post" name="modifyGenreForm">
                            <h1 style='color:orange'> Modification du genre </h1> 
                            <!-- Liste déroulante pour sélectionner le genre à modifier -->
                            <select name="genreToModify">
                                <?php
                                // Afficher la liste des genres
                                $genreResult = $conn->query("SELECT id, nom FROM genre");
                                while ($row = $genreResult->fetch_assoc()) {
                                    echo "<option value=\"{$row['id']}\">{$row['nom']}</option>";
                                }
                                ?>
                            </select>

                            <!-- Champ pour entrer le nouveau nom du genre -->
                            <input type="text" class="box-input" name="newGenrename" placeholder="Nouveau nom du genre" required />

                            <!-- Bouton de confirmation pour modifier le genre -->
                            <input type="submit" name="modifyGenreSubmit" value="Modifier" style='color:black' class="box-button" />
                        </form>
                    </div>

                    <?php
                    // Traitement de la modification du genre
                    if (isset($_POST['modifyGenreSubmit'])) {
                        $genreIdToModify = $_POST['genreToModify'];
                        $newGenrename = $_POST['newGenrename'];

                        // Requête pour mettre à jour le genre sélectionné
                        $modifyGenreQuery = "UPDATE genre SET nom='$newGenrename' WHERE id='$genreIdToModify'";
                        $modifyGenreResult = $conn->query($modifyGenreQuery);

                        if ($modifyGenreResult) {
                            header("Location: {$_SERVER['PHP_SELF']}");
                        }
                    } 
                    ?>

                    <!-- AJOUT D'UN LIVRE DANS UN GENRE -->
                    <div class="ges-item">
                        <form class="box" action="" method="post" name="choixLivreForm">
                            <h1 style='color:purple'> Ajout d'un livre dans un Genre </h1> 
                            <!-- Liste déroulante pour sélectionner le livre à ajouter -->
                            <select name="livreToGenre">
                                <?php
                                // Afficher la liste des livres
                                $livreResult = $conn->query("SELECT id, nom FROM livre");
                                while ($row = $livreResult->fetch_assoc()) {
                                    echo "<option value=\"{$row['id']}\">{$row['nom']}</option>";
                                }
                                ?>
                            </select>

                            <!-- Liste déroulante pour sélectionner le genre -->
                            <select name="genreToApply">
                                <?php
                                // Afficher la liste des genres
                                $genreResult = $conn->query("SELECT id, nom FROM genre");
                                while ($row = $genreResult->fetch_assoc()) {
                                    echo "<option value=\"{$row['id']}\">{$row['nom']}</option>";
                                }
                                ?>
                            </select>

                            <!-- Bouton de confirmation pour ajouter un livre dans un genre -->
                            <input type="submit" name="AjoutlivreSubmit" value="Ajouter" style='color:black' class="box-button" />
                        </form>
                    </div>

                    <?php
                    // Traitement de l'ajout du livre dans le genre
                    if (isset($_POST['AjoutlivreSubmit'])) {
                        $genreToApply = $_POST['genreToApply'];
                        $livreToGenre = $_POST['livreToGenre'];

                        // Requête pour mettre à jour le genre du livre sélectionné
                        $modifyGenreLivreQuery = "UPDATE livre SET idgenre = $genreToApply WHERE id = $livreToGenre";
                        $modifyGenreLivreResult = $conn->query($modifyGenreLivreQuery);

                        if ($modifyGenreLivreResult) {
                            header("Location: {$_SERVER['PHP_SELF']}");
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


      <?php
            if (isset($messageErreur)) {
                echo '<p style="color: red;">' . $messageErreur . '</p>';} // Style pour le message d'erreur
            elseif (isset($messageSucces)) {
                echo '<p style="color: green;">' . $messageSucces . '</p>';} // Style pour le message de succes 
        ?>



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

<?php ob_end_flush(); ?>