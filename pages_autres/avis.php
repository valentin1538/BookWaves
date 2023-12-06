<?php
// Connexion à la base de données
$servername = "localhost"; // Remplacez par le nom de votre serveur de base de données
$username = "root"; // Remplacez par votre nom d'utilisateur de base de données
$password = ""; // Remplacez par votre mot de passe de base de données
$database = "Biblio"; // Remplacez par le nom de votre base de données

// Créez la connexion à la base de données
$conn = new mysqli($servername, $username, $password, $database);

// Vérifiez la connexion
if ($conn->connect_error) {
  die("La connexion à la base de données a échoué : " . $conn->connect_error);
}
// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion

// Requête pour récupérer les données des tables
$idLivre = isset($_GET['id']) ? $_GET['id'] : '';

$sqlAvis = "SELECT avis.commentaire, avis.note, users.username 
            FROM avis 
            INNER JOIN users ON avis.idpersonne = users.id 
            WHERE avis.idlivre = $idLivre";

$resultAvis = $conn->query($sqlAvis);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bibliothèque</title>
  <!-- Bootstrap core CSS -->
  <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="../css/style.css" rel="stylesheet">
  <link href="../css/style-responsive.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/cf0cc41982.js" crossorigin="anonymous"></script>
  <script src="../lib/visualiser/zip.min.js"></script>
  <script src="../lib/visualiser/epub.js"></script>

  <link rel="stylesheet" type="text/css" href="../css/visualiser.css" />
</head>

<body>
  <section id="container">
    <!-- **********************************************************************************************************************************************************
          TOP BAR CONTENT & NOTIFICATIONS
          *********************************************************************************************************************************************************** -->
    <!--header start-->
    <header class="header black-bg text-center">
      <!--logo start-->
      <a href="../index.php" class="logo"><b><span>BOOK WAVES
            <?php echo isset($_SESSION['username']) ? ' / ' . $_SESSION['username'] : ''; ?>
          </span></b></a>
      <!--logo end-->
      <!--  Categories start -->
      <div class="nav notify-row text-center" id="top_menu">
        <!--  Categories start -->
        <ul class="nav top-menu">
          <!-- Ajout Livre Boutton start -->
          <li id="header_ajout_livre_bar" class="dropdown">

            <a href="ajout_avis.php?id=<?php echo $idLivre; ?>" class="btn btn-primary">
              Ajouter un avis
              <i class="fa-solid fa-book-medical"></i>
            </a>
            
          <li id="header_convertir_livre_bar" class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
              Recupération des actualités
              <i class="fa-solid fa-newspaper"></i>
            </a>
            <ul class="dropdown-menu extended notification">
              <div class="notify-arrow notify-arrow-green"></div>
              <li>
                <a href="#">
                  <span class="label label-danger"><i class="fa fa-calendar"></i></span>
                  Charger les actualités
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <ul class="nav pull-right top-menu">
        <?php if (isset($_SESSION['username'])): ?>
          <!-- Utilisateur connecté -->
          <li><a class="logout"
              href="../pages_cnx/logout.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Se Déconnecter</a>
          </li>
        <?php else: ?>
          <!-- Utilisateur non connecté -->
          <li><a class="logout" href="../pages_cnx/login.php">Se Connecter</a></li>
        <?php endif; ?>
      </ul>
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
            <ul id="menuDeroulantCommun" class="menu-deroulant-commune" style="display: block;>
                          <li class=" auteur">
              <a href="../index.php" class="active">
                <i class="fa fa-book-open"></i>
                <span>Livres</span>
              </a>
          </li>
          <li class="auteur">
            <a href="../pages_commune/auteurs_commune.php">
              <i class="fa fa-user-tie"></i>
              <span>Auteurs</span>
            </a>
          </li>
          <li class="editeur">
            <a href="../pages_commune/editeurs_commune.php">
              <i class="fa fa-feather"></i>
              <span>Editeurs</span>
            </a>
          </li>
          <li class="Genres">
            <a href="../pages_commune/genres_commune.php">
              <i class="fa fa-tags"></i>
              <span>Genres</span>
            </a>
          </li>
        </ul>
        </li>
        <li class="sub-menu">
          <a href="#" id="biblioPersoLink" class="menu-link">
            <i class="fa fa-book"></i>
            <span>Bibliothèque Perso</span>
          </a>
          <ul id="menuDeroulantPerso" class="menu-deroulant-perso">
            <li class="auteur">
              <a href="../pages_perso/livres_perso.php">
                <i class="fa fa-book-open"></i>
                <span>Livres</span>
              </a>
            </li>
            <li class="auteur">
              <a href="../pages_perso/auteurs_perso.php">
                <i class="fa fa-user-tie"></i>
                <span>Auteurs</span>
              </a>
            </li>
            <li class="editeur">
              <a href="../pages_perso/editeurs_perso.php">
                <i class="fa fa-feather"></i>
                <span>Editeurs</span>
              </a>
            </li>
            <li class="Genres">
              <a href="../pages_perso/genres_perso.php">
                <i class="fa fa-tags"></i>
                <span>Genres</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="sub-menu">
          <a href="../pages_autres/livresRecherche.php">
            <i class="fa fa-globe"></i>
            <span>Recherche d'Ebook</span>
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

<!--main content start-->
<section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="main-chart">
            <!--CUSTOM CHART START -->
            <div class="border-head">
            <?php
              echo '<div class="container">';
              if ($resultAvis->num_rows > 0) {
                  while ($row = $resultAvis->fetch_assoc()) {
                      echo '<div class="book">';
                      echo "<p>Utilisateur : " . $row['username'] . "</p>";
                      echo "<p>Commentaire : " . $row['commentaire'] . "</p>";
                      echo "<p>Note : " . $row['note'] . "</p>";
                      echo "</div>";
                  }
              } else {
                  echo "<p>Aucun avis pour ce livre.</p>";
              }
              echo '</div>';
              ?>
              </div>

              <!-- Vos autres éléments, scripts, etc. -->
            </div>
            <!--custom chart end-->
          </div>
          <!-- /col-lg-3 -->
        </div>
        <!-- /row -->
      </section>
    </section>
    <!--main content end-->

</body>

</html>