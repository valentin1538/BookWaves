<?php
// SOUS PROJET GAUTHIER ET VALENTIN PERREIRA

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


?>

<!DOCTYPE html>
<html lang="fr">

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
  <link href="../css/creation_Book.css" rel="stylesheet">

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
      <a href="../index.php" class="logo"><b><span>BOOK WAVES
            <?php echo isset($_SESSION['username']) ? ' / ' . $_SESSION['username'] : ''; ?>
          </span></b></a>
      <!--logo end-->
      <div class="nav notify-row text-center" id="top_menu">
        <!--  Categories start -->
        <ul class="nav top-menu">
          <!-- Ajout Livre Boutton start -->
          
        </ul>
      </div>
      <div class="top-menu">
        <ul class="nav pull-right top-menu">
          <?php if (isset($_SESSION['username'])): ?>
            <!-- Utilisateur connecté -->
            <li><a class="logout"
                href="./pages_cnx/logout.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Déconnexion</a>
            </li>
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
                <a href="../index.php">
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
                <a href="../pages_perso/livres_perso.php" class="active">
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
          <li class="sub-menu">
            <a href="./creationEbook.php" class="active">
              <i class="fa-solid fa-plus"></i>
              <span>Créer un livre</span>
            </a>
          </li>
          <li class="sub-menu">
            <a href="../pages_forum/forum.php">
              <i class="fa fa-rectangle-list"></i>
              <span>Forums</span>
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
              <!-- Formulaire pour créer un livre ePub -->
              <h2 class="center-heading">Formulaire pour créer un livre ePub</h2>
              <!-- Ajoutez ceci à l'endroit où vous souhaitez afficher le message de succès -->
              <div class="center-heading" id="messageContainer"></div>
              <form id="creationBookForm" action="./traitementCreationBook.php" method="post" enctype="multipart/form-data">
                  <label for="title">Nom du livre :</label>
                  <input type="text" id="title" name="title" required>

                  <!-- Utilisateur connecté (champ verrouillé) -->
                  <label for="creator">Nom de l'auteur :</label>
                  <input type="text" id="creator" name="creator" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" readonly required>

                  <label for="publisher">Nom de l'éditeur :</label>
                  <select id="publisher" name="publisher" required>
                      <?php
                      $queryEditeurs = $conn->query("SELECT * FROM editeur");
                      while ($editeur = $queryEditeurs->fetch_assoc()) {
                          echo "<option value='" . $editeur['id'] . "'>" . $editeur['nom'] . "</option>";
                      }
                      ?>
                  </select>

                  <label for="subject">Genre :</label>
                  <select id="subject" name="subject" required>
                      <?php
                      $queryGenres = $conn->query("SELECT * FROM genre");
                      while ($genre = $queryGenres->fetch_assoc()) {
                          echo "<option value='" . $genre['id'] . "'>" . $genre['nom'] . "</option>";
                      }
                      ?>
                  </select>

                  <label for="language">Langue :</label>
                  <select id="language" name="language" required>
                      <?php
                      $queryLangues = $conn->query("SELECT * FROM langue");
                      while ($langue = $queryLangues->fetch_assoc()) {
                          echo "<option value='" . $langue['id'] . "'>" . $langue['nom'] . "</option>";
                      }
                      ?>
                  </select>

                  <label for="contenu">Contenu :</label>
                  <textarea id="contenu" name="contenu" required></textarea>

                  <label for="coverImage">Image de couverture :</label>
                  <input type="file" id="coverImage" name="coverImage" accept="image/*">

                  <input type="submit" value="Créer ePub">
              </form>
               <!-- Fin du formulaire pour créer un livre ePub -->
            </div>
            <!--custom chart end-->
          </div>
          <!-- /col-lg-3 -->
        </div>
        <!-- /row -->
      </section>
    </section>
    <!--main content end-->
    
    <script>
      document.getElementById("creationBookForm").addEventListener("submit", function (event) {
          event.preventDefault(); // Empêche le rechargement de la page

          // Créez une nouvelle instance de l'objet FormData pour récupérer les données du formulaire
          var formData = new FormData(this);

          // Créez une nouvelle instance de l'objet XMLHttpRequest
          var xhr = new XMLHttpRequest();

          // Configurez la requête
          xhr.open("POST", "./traitementCreationBook.php", true);

          // Gérez l'événement de fin de requête
          xhr.onload = function () {
              if (xhr.status === 200) {
                  // Gérez la réponse du serveur ici
                  console.log(xhr.responseText);
                  // Affichez le message de succès ou d'erreur dans la div
                  document.getElementById("messageContainer").innerHTML = xhr.responseText;

              } else {
                  // Gérez les erreurs ici
                  console.error("Une erreur est survenue lors de la requête.");
              }
          };

          // Envoyez la requête avec les données du formulaire
          xhr.send(formData);
      });
    </script>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="../lib/jquery/jquery.min.js"></script>

    <script src="../lib/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>