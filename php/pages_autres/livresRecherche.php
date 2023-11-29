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
<?php

// Requête pour récupérer les données des tables
$sql = "SELECT livre.id, livre.nom, livre.infos, auteur.nom as nom_auteur, editeur.nom as nom_editeur, genre.nom as nom_genre, langue.nom as nom_langue FROM livre
        INNER JOIN auteur ON livre.idauteur = auteur.id
        INNER JOIN editeur ON livre.idediteur = editeur.id
        INNER JOIN genre ON livre.idgenre = genre.id
        INNER JOIN langue ON livre.idlangue = langue.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
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
    <style>
        /* Styles CSS pour la représentation visuelle des livres */

        form {
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            font-size: 18px;
            margin-right: 10px;
        }
        input[type="text"], input[type="submit"] {
            padding: 10px;
            font-size: 16px;
        }
        input[type="submit"] {
            cursor: pointer;
        }
        input[type="submit"]:hover {

        }
        .container-2 {
            width: 80%;
            margin: 0 auto;

            padding: 20px;

            border-radius: 5px;
            padding-left: 150px;
        }
        .books {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .book {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;

            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .book img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .book h2 {
            font-size: 16px;
            margin-top: 10px;

        }
        .book p {
            font-size: 14px;

            margin-top: 5px;
        }
        .book a {

            text-decoration: none;
        }
        .book a:hover {
            text-decoration: underline;
        }
    </style>
</head>


<body>
<section id="container">
    <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
    <!--header start-->
    <header class="header black-bg text-center">
      <!--logo start-->
      <a href="../index.php" class="logo"><b><span>BOOK WAVES <?php echo isset($_SESSION['username']) ? ' / ' . $_SESSION['username'] : ''; ?></span></b></a>
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
          <li><a class="logout" href="../pages_cnx/logout.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Se Déconnecter</a></li>
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
                    <a href="../pages_autres/livresRecherche.php" class="active">
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
                <h3>Recherche de Livres</h3>

                <form method="GET" class="barre-recherche">
                    <input type="text" name="recherche" placeholder="Rechercher sur internet">
                    <input type="submit" value="Rechercher">
                    
                </form>

                    <?php


                    if (isset($_GET['recherche']) && !empty($_GET['recherche'])) {
                        $searchTerm = $_GET['recherche'];
                        $recherche = urlencode($searchTerm);
                        // Récupérer la date du 1er janvier de cette année
                        $date = date('Y') . "-01-01";
                        $api_url = "https://www.googleapis.com/books/v1/volumes?q=intitle:$recherche&langRestrict=fr&orderBy=newest&printType=books&filter=partial&projection=lite&publishedDate=$date";

                        $response = file_get_contents($api_url);

                        if ($response === false) {
                            echo "Erreur lors de la récupération des données.";
                        } else {
                            $data = json_decode($response, true);

                            if (isset($data['items'])) {
                                echo "<p style='padding-left: 10px;'>Résultats pour la recherche : '$searchTerm'</p>";

                                $books = $data['items'];
                                echo "<div class='container'>";
                                $printedTitles = []; // Tableau pour stocker les titres déjà imprimés
                                foreach ($books as $item) {
                                    $volumeInfo = $item['volumeInfo'];
                                    // Vérifier si le titre a déjà été imprimé
                                    if (!in_array($volumeInfo['title'], $printedTitles, true)) {
                                        echo "<div class='book'>";
                                        if (isset($volumeInfo['imageLinks']) && isset($volumeInfo['imageLinks']['thumbnail'])) {
                                            echo "<img src='" . $volumeInfo['imageLinks']['thumbnail'] . "' alt='Couverture Livre'>";
                                        } else {
                                            echo "<img src='https://via.placeholder.com/150x200' alt='Couverture Livre'>";
                                        }
                                        echo "<h2>" . $volumeInfo['title'] . "</h2>";
                                        if (isset($volumeInfo['authors'])) {
                                            echo "<p>Auteur(s): " . implode(", ", $volumeInfo['authors']) . "</p>";
                                        }
                                        if (isset($volumeInfo['publishedDate'])) {
                                            $date = date("d-m-Y", strtotime($volumeInfo['publishedDate']));
                                            echo "<p>Date de publication: " . $date . "</p>";
                                        }
                                        if (isset($volumeInfo['previewLink'])) {
                                            echo "<p><a href='" . $volumeInfo['previewLink'] . "' target='_blank'>Voir sur Google Books</a></p>";
                                        }
                                        if (isset($item['saleInfo']['buyLink'])) {
                                            echo "<h3>Où acheter:</h3> <a href='" . $item['saleInfo']['buyLink'] . "'><img src='logo google.png' alt='Google Logo'></a> ";


                                            if (isset($item['saleInfo']['listPrice']['amount']) && isset($item['saleInfo']['listPrice']['currencyCode'])) {
                                                echo "<h3>".$item['saleInfo']['listPrice']['amount'] . " " .  $item['saleInfo']['listPrice']['currencyCode']. "<h3>" . "<br>";
                                            } else {
                                                echo "Prix non disponible<br>";
                                            }
                                        } else {
                                            echo "<h3>Non disponible à l'achat dans la bibliothèque ebook Google.</h3>";
                                            echo "<br>";
                                        }


                                        echo "</div>";
                                        $printedTitles[] = $volumeInfo['title']; // Ajouter le titre à la liste des titres imprimés
                                    }
                                }
                                echo "</div>";
                            } else {
                                echo "Aucun livre trouvé pour la recherche : '$searchTerm'";
                            }
                        }
                    } elseif (empty($_GET['recherche']) && isset($_SESSION['previousResults'])) {
                        unset($_SESSION['previousResults']);
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

    <script>
    </script>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="../lib/jquery/jquery.min.js"></script>

    <script src="../lib/bootstrap/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="../lib/jquery.dcjqaccordion.2.7.js"></script>
    <script src="../lib/jquery.scrollTo.min.js"></script>
    <script src="../lib/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="../lib/jquery.sparkline.js"></script>
    <!--common script for all pages-->
    <script src="../lib/common-scripts.js"></script>
    <script type="text/javascript" src="../lib/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="../lib/gritter-conf.js"></script>
    <!--script for this page-->
    <script src="../lib/sparkline-chart.js"></script>
    <script src="../lib/zabuto_calendar.js"></script>
</body>
</html>