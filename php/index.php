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


?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bibliothèque</title>
  <!-- Bootstrap core CSS -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/cf0cc41982.js" crossorigin="anonymous"></script>
</head>


<body>
  <section id="container">
    <!-- **********************************************************************************************************************************************************
          TOP BAR
          *********************************************************************************************************************************************************** -->
    <!--header start-->
    <header class="header black-bg">
      <!--logo start-->
      <a href="index.php" class="logo"><b><span>BOOK WAVES
            <?php echo isset($_SESSION['username']) ? ' / ' . $_SESSION['username'] : ''; ?>
          </span></b></a>
      <!--logo end-->
      <div class="top-menu">
        <ul class="nav pull-right top-menu">
          <?php if (isset($_SESSION['username'])): ?>
            <!-- Utilisateur connecté -->
            <li><a class="logout" href="./pages_profil/profil.php">Profil</a></li>
            <li><a class="logout"
                href="./pages_cnx/logout.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Se
                Déconnecter</a>
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
          SIDEBAR DE NAVIGATION
          *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <aside>
      <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
          <li class="sub-menu">
            <a href="#" id="biblioCommuneLink" class="menu-link">
              <i class="fa fa-book"></i>
              <span>Bibliothèque Commune</span>
            </a>
            <ul id="menuDeroulantCommun" class="menu-deroulant-commune" style="display: block;">
              <li class="auteur">
                <a href="./index.php" class="active">
                  <i class="fa fa-book-open"></i>
                  <span>Livres</span>
                </a>
              </li>
              <li class="auteur">
                <a href="./pages_commune/auteurs_commune.php">
                  <i class="fa fa-user-tie"></i>
                  <span>Auteurs</span>
                </a>
              </li>
              <li class="editeur">
                <a href="./pages_commune/editeurs_commune.php">
                  <i class="fa fa-feather"></i>
                  <span>Editeurs</span>
                </a>
              </li>
              <li class="Genres">
                <a href="./pages_commune/genres_commune.php">
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
                <a href="./pages_perso/livres_perso.php">
                  <i class="fa fa-book-open"></i>
                  <span>Livres</span>
                </a>
              </li>
              <li class="auteur">
                <a href="./pages_perso/auteurs_perso.php">
                  <i class="fa fa-user-tie"></i>
                  <span>Auteurs</span>
                </a>
              </li>
              <li class="editeur">
                <a href="./pages_perso/editeurs_perso.php">
                  <i class="fa fa-feather"></i>
                  <span>Editeurs</span>
                </a>
              </li>
              <li class="Genres">
                <a href="./pages_perso/genres_perso.php">
                  <i class="fa fa-tags"></i>
                  <span>Genres</span>
                </a>
              </li>
            </ul>
          </li>
          <li class="sub-menu">
            <a href="./pages_autres/livresRecherche.php">
              <i class="fa fa-globe"></i>
              <span>Recherche d'Ebook</span>
            </a>
          </li>
          <li class="sub-menu">
            <a href="./pages_forum/forum.php">
              <i class="fa fa-rectangle-list"></i>
              <span>Forums</span>
            </a>
          </li>
        </ul>
        <!-- sidebar menu end-->
      </div>

      <script>
        // SCRIPT PERMETTANT LE DYNAMISME DE LA SIDEBAR
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

    <!-- **********************************************************************************************************************************************************
          MAIN CONTENT
          *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="main-chart">
            <!--CUSTOM CHART START -->
            <div class="border-head">
              <h3>BIBLIOTHEQUE GLOBALE</h3>
              <?php
              // RECHERCHE DES LIVRES PAR NOMS DANS LA BASE (Clément)
              try {
                $connexion = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Récupérer le terme de recherche depuis l'URL
                $recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';

                // Requête pour récupérer les livres filtrés par le terme de recherche
                $requete = "SELECT livre.id AS id, livre.lienfiles AS lien, livre.lienfolder AS nomfichier, livre.nom AS nom, auteur.nom AS auteur, editeur.nom AS editeur, genre.nom AS genre, langue.nom AS langue
                                  FROM livre 
                                  JOIN auteur ON livre.idauteur = auteur.id 
                                  JOIN editeur ON livre.idediteur = editeur.id 
                                  JOIN genre ON livre.idgenre = genre.id 
                                  JOIN langue ON livre.idlangue = langue.id 
                                  WHERE livre.nom LIKE :recherche";

                $stmt = $connexion->prepare($requete);
                $stmt->bindValue(':recherche', "%$recherche%", PDO::PARAM_STR);
                $stmt->execute();

                $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
              } catch (PDOException $e) {
                echo "Erreur de connexion : " . $e->getMessage();
              }

              // Affichage du formulaire de recherche
              $formulaireRecherche = '
                      <form class="barre-recherche" action="" method="GET">
                          <input type="text" name="recherche" placeholder="Rechercher un livre" value="' . htmlspecialchars($recherche) . '">
                          <input type="submit" value="Rechercher">
                      </form>';
              echo $formulaireRecherche;

              // AFFICHAGE DYNAMIQUE DES LIVRES (Valentin Prevot)
              echo '<div class="container">';
              foreach ($livres as $livre) {
                echo '<div class="book">';
                echo '<div class="title-bar">';
                echo '<h2>' . (isset($livre['nom']) ? htmlspecialchars($livre['nom']) : 'Inconnu') . '</h2>';
                echo '<div id="header_ajout_livre_bar" class="dropdown bars">';
                echo '<a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">';
                echo '<i class="fa-solid fa-bars"></i>';
                echo '</a>';
                echo '<ul class="dropdown-menu extended notification">';
                echo '<div class="notify-arrow notify-arrow-grey"></div>';
                echo '<li>';
                echo '<a href="./pages_autres/visualiser.php?nomfichier=' . urlencode($livre['nomfichier']) . '"><i class="fa fa-eye"></i> Visualiser</a>';
                echo '</li>';
                echo '<li>';
                echo '<a href="#" onclick="addToPersonalLibrary(' . $livre['id'] . ')"> <i class="fa fa-square-plus"></i> Ajouter à ma bilbiothèque</a>';
                echo '</li>';
                echo '<li>';
                echo '<a href="./pages_autres/avis.php?id=' . urlencode($livre['id']) . '&nom=' . urlencode($livre['nom']) . '"><i class="fa fa-pencil"></i> Avis</a>';
                echo '</li>';
                echo '</ul>';
                echo '</div>';
                echo '</div>';
                echo '<button class="btn-info" onclick="ShowBookInfo(' . $livre['id'] . ')">Info</button>';
                echo '</div>';
              }

              echo '</div>';
              ?>
            </div>
            <!--custom chart end-->
          </div>
          <!-- /col-lg-3 -->
        </div>
        <!-- /row -->
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
        xhr.open('GET', './pages_autres/get_book_info_commun.php?id=' + bookId, true);
        xhr.send();
      }

      // FONCTION QUI AFFICHE LA SIDEBAR D'INFOS D'UN LIVRE (Valentin Prevot)
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

      // FONCTION QUI AJOUTE UN LIVRE DE LA BILBLIOTHEQUE GLOBALE A LA PERSO DE L'UTILISATEUR (Valentin Prevot)
      function addToPersonalLibrary(bookId) {
        // Utilisez AJAX pour envoyer la demande d'ajout
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
          if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
              // Affichez un message de confirmation
              alert('Livre ajouté à votre bibliothèque personnelle avec succès!');
            }
            if (xhr.status === 400) {
              // Affichez un message d'erreur en cas d'échec
              alert('Ce livre est déjà dans votre bibliothèque personnelle.');
            }
            if (xhr.status === 401) {
              // Affichez un message d'erreur en cas d'échec
              alert('Vous devez vous connecté pour pouvoir ajouter ce livre dans votre bibliothèque personnelle.');
            }
          }
        };

        // Envoyez une requête POST au script PHP côté serveur
        xhr.open('POST', './php_sql/add_to_personal_library.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('bookId=' + bookId);
      }

    </script>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>