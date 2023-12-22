<?php
// SOUS PROJET MATHEO ET BENJAMIN

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
if (!isset($_SESSION["username"])) {
  header("Location: ../pages_cnx/login.php");
  exit();
}
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
  <script src="https://kit.fontawesome.com/cf0cc41982.js" crossorigin="anonymous"></script>
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

            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
              Ajouter
              <i class="fa-solid fa-book-medical"></i>
            </a>
            <ul class="dropdown-menu extended notification">
              <div class="notify-arrow notify-arrow-green"></div>
              <li>
                <button id="add-book"><span class="label label-success"><i class="fa fa-plus"></i></span>
                  Ajout depuis un dossier unique</button>
                <input type="file" id="file-input" accept=".epub" style="display: none">
              </li>
            </ul>
          </li>
          <li id="header_ajout_livre_bar" class="dropdown bars">

            <a href="../pages_autres/conversion.php">
              Convertir
              <i class="fa-solid fa-arrows-rotate"></i>
            </a>
          </li>
        </ul>
      </div>
      <ul class="nav pull-right top-menu">
        <?php if (isset($_SESSION['username'])): ?>
          <!-- Utilisateur connecté -->
          <li><a class="logout" href="../pages_profil/profil.php">Profil</a></li>
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
            <ul id="menuDeroulantPerso" class="menu-deroulant-perso" style="display: block;">
              <li class="auteur">
                <a href="./livres_perso.php">
                  <i class="fa fa-book-open"></i>
                  <span>Livres</span>
                </a>
              </li>
              <li class="auteur">
                <a href="./auteurs_perso.php" class="active">
                  <i class="fa fa-user-tie"></i>
                  <span>Auteurs</span>
                </a>
              </li>
              <li class="editeur">
                <a href="./editeurs_perso.php">
                  <i class="fa fa-feather"></i>
                  <span>Editeurs</span>
                </a>
              </li>
              <li class="Genres">
                <a href="./genres_perso.php">
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
          <?php if (isset($_SESSION['username'])): ?>
          <li class="sub-menu">
            <a href="../pages_autres/creationEbook.php">
              <i class="fa-solid fa-plus"></i>
              <span>Créer un livre</span>
            </a>
          </li>
          <?php endif; ?>
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
              <h3>AUTEURS</h3>
              <div class="container">
                <?php
                $conn->set_charset("utf8");

                // Requête pour récupérer les editeurs
                $queryauteurs = "SELECT id, nom FROM auteur WHERE id != 1";
                $resultauteurs = $conn->query($queryauteurs);

                if ($resultauteurs && $resultauteurs->num_rows > 0) {
                  while ($roweauteur = $resultauteurs->fetch_assoc()) {
                    $auteurId = htmlspecialchars($roweauteur['id']);
                    $auteurName = htmlspecialchars($roweauteur['nom']);

                    echo '<div class="book">';
                    echo "<a href='#' class='auteur-link' data-auteur-id='$auteurId'>$auteurName</a>";
                    echo '</div>';
                  }
                } else {
                  echo "Aucun auteur trouvé dans la base de données.";
                }

                echo '</div>';

                // Affiche la liste des livres du editeur sélectionné
                echo '<div class="livres-list" id="livres-list">';
                echo '</div>';

                ?>
              </div>

            </div>
            <!-- /col-lg-3 -->
          </div>
          <!-- /row -->
        </div>

      </section>
    </section>
    <!--main content end-->

        <!-- **********************************************************************************************************************************************************
      SIDEBAR INFOS LIVRE (Valentin Prevot)
      *********************************************************************************************************************************************************** -->
    <!-- Sidebar for Book Info -->
    <aside id="bookInfoSidebar" class="book-info-sidebar">
      <!-- Le contenu des informations du livre sera affiché ici -->


    </aside>

    <script>

      // FONCTION QUI AFFICHE LES INFORMATIONS D'UN LIVRE (Valentin Prevot)
      function ShowBookInfo(bookId) {
        // Utilisez AJAX pour récupérer les informations du livre du serveur
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
          if (xhr.readyState === XMLHttpRequest.DONE) {
            console.log(xhr.responseText); // Afficher la réponse dans la console
            if (xhr.status === 200) {
              // Parsez les données JSON reçues du serveur
              var bookInfo = JSON.parse(xhr.responseText);

              // Construisez le contenu HTML avec les informations du livre
              var bookInfoHTML = `
                    <div class="info-content">
                    <h2 style = "text-align: center;">${bookInfo.nom}</h2>
                    <p><strong>Auteur:</strong> ${bookInfo.auteur}</p>
                    <p><strong>Editeur:</strong> ${bookInfo.editeur}</p>
                    <p><strong>Genre:</strong> ${bookInfo.genre}</p>
                    <p><strong>Langue:</strong> ${bookInfo.langue}</p>
                    <!-- Ajoutez d'autres informations du livre ici -->
                    </div>
                    <!-- Ajoutez une flèche (ou une icône) visible en permanence à gauche de la sidebar -->
                    <div id="expandArrow" onclick="toggleBookInfo()">
                      <!-- Utilisez une icône de flèche, par exemple, une flèche vers la droite -->
                      <i class="fa fa-chevron-left"></i>
                    </div>
                `;

              // Affichez le contenu dans la section latérale
              document.getElementById('bookInfoSidebar').innerHTML = bookInfoHTML;

              // Faites en sorte que la section latérale soit visible
              var arrow = document.getElementById('expandArrow');
              document.getElementById('bookInfoSidebar').style.width = '300px';
              document.getElementById('main-content').style.marginRight = '300px';
              arrow.classList.add('open');
            } else {
              console.error('Erreur lors de la récupération des informations du livre.');
            }
          }
        };

        // Envoyez une requête GET vers votre script PHP qui récupère les informations du livre
        xhr.open('GET', '../pages_autres/get_book_info_perso.php?id=' + bookId, true);
        xhr.send();
      }
    </script>


    <script>

      document.addEventListener('DOMContentLoaded', function () {
        var auteurLinks = document.querySelectorAll('.auteur-link');
        var livresList = document.getElementById('livres-list');

        var currentPageLink = document.querySelector('.menu-deroulant-commune .active a');
        var currentPageCategory = currentPageLink ? currentPageLink.getAttribute('href').replace('./', '') : '';

        auteurLinks.forEach(function (link) {
          link.addEventListener('click', function (event) {
            event.preventDefault();
            var auteurId = link.getAttribute('data-auteur-id');
            chargerLivresParediteur(auteurId);
          });
        });

        function chargerLivresParediteur(auteurId, category) {
          // Exécute une requête AJAX pour charger les livres du editeur sélectionné
          var xhr = new XMLHttpRequest();
          xhr.open('GET', 'charger-livres-par-auteur.php?auteurId=' + auteurId + '&category=' + category, true)

          xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 400) {
              // La requête a réussi, mettez à jour la liste des livres
              livresList.innerHTML = xhr.responseText;
            } else {
              console.error('La requête a échoué.');
            }
          };

          xhr.onerror = function () {
            console.error('Erreur réseau.');
          };

          xhr.send();
        }
      });

    </script>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="../lib/jquery/jquery.min.js"></script>

    <script src="../lib/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>