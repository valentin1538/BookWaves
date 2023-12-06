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
$sql = "SELECT livre.id, livre.nom, livre.infos, auteur.nom as nom_auteur, editeur.nom as nom_editeur, genre.nom as nom_genre, langue.nom as nom_langue FROM livre
        INNER JOIN auteur ON livre.idauteur = auteur.id
        INNER JOIN editeur ON livre.idediteur = editeur.id
        INNER JOIN genre ON livre.idgenre = genre.id
        INNER JOIN langue ON livre.idlangue = langue.id";

$result = $conn->query($sql);
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
            <ul id="menuDeroulantCommun" class="menu-deroulant-commune" style="display: block;">
              <li class="auteur">
                <a href="../index.php">
                  <i class="fa fa-book-open"></i>
                  <span>Livres</span>
                </a>
              </li>
              <li class="auteur">
                <a href="./auteurs_commune.php">
                  <i class="fa fa-user-tie"></i>
                  <span>Auteurs</span>
                </a>
              </li>
              <li class="editeur">
                <a href="./editeurs_commune.php">
                  <i class="fa fa-feather"></i>
                  <span>Editeurs</span>
                </a>
              </li>
              <li class="Genres">
                <a href="./genres_commune.php" class="active">
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
              <h3>GENRES</h3>
              <div class="container">
                <?php

                // Requête pour récupérer les genres
                $queryGenres = "SELECT id, nom FROM genre WHERE id != 1";
                $resultGenres = $conn->query($queryGenres);

                if ($resultGenres && $resultGenres->num_rows > 0) {
                  while ($rowGenre = $resultGenres->fetch_assoc()) {
                    $genreId = $rowGenre['id'];
                    $genreName = $rowGenre['nom'];

                    echo '<div class="book">';
                    echo "<a href='#' class='genre-link' data-genre-id='$genreId'>$genreName</a>";
                    echo '</div>';
                  }
                } else {
                  echo "Aucun genre trouvé dans la base de données.";
                }

                echo '</div>';

                // Affiche la liste des livres du genre sélectionné
                echo '<div class="livres-list" id="livres-list">';
                echo '</div>';

                ?>
              </div>
            </div>
            <!-- /col-lg-3 -->
          </div>
          <!-- /row -->
      </section>
    </section>
    <!--main content end-->

    <!-- Sidebar for Book Info -->
    <aside id="bookInfoSidebar" class="book-info-sidebar">
      <!-- Le contenu des informations du livre sera affiché ici -->


    </aside>

    <script>
      function showBookInfo(bookId) {
        // Utilisez AJAX pour récupérer les informations du livre du serveur
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
          if (xhr.readyState === XMLHttpRequest.DONE) {
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
        xhr.open('GET', '../pages_autres/get_book_info_commun.php?id=' + bookId, true);
        xhr.send();
      }

      function toggleBookInfo() {
        var sidebar = document.getElementById('bookInfoSidebar');
        var arrow = document.getElementById('expandArrow');

        // Si la sidebar est ouverte, la fermer ; sinon, l'ouvrir
        if (sidebar.style.width === '0px' || sidebar.style.width === '') {
          sidebar.style.width = '250px'; // Réglez la largeur souhaitée de la sidebar
          arrow.classList.add('open'); // Ajoutez une classe pour styliser la flèche en tant qu'ouverte
        } else {
          sidebar.style.width = '0';
          arrow.classList.remove('open'); // Retirez la classe pour styliser la flèche en tant que fermée
          arrow.style.left = '50px';
          document.getElementById('main-content').style.marginRight = '0';
        }
      }
    </script>



    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var genreLinks = document.querySelectorAll('.genre-link');
        var livresList = document.getElementById('livres-list');

        genreLinks.forEach(function (link) {
          link.addEventListener('click', function (event) {
            event.preventDefault();
            var genreId = link.getAttribute('data-genre-id');
            chargerLivresParGenre(genreId);
          });
        });

        function chargerLivresParGenre(genreId) {
          // Exécute une requête AJAX pour charger les livres du genre sélectionné
          var xhr = new XMLHttpRequest();
          xhr.open('GET', 'charger-livres-par-genre.php?genreId=' + genreId, true);

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
    <script class="include" type="text/javascript" src="../lib/jquery.dcjqaccordion.2.7.js"></script>
    <script src="../lib/jquery.scrollTo.min.js"></script>


</body>

</html>