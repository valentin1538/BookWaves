<!DOCTYPE html>
<html>
<!-- SOUS PROJET YOUNES -->
<?php
$lienepub = isset($_GET['nomfichier']) ? $_GET['nomfichier'] : '';
?>

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
          TOP BAR CONTENT
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
          SIDEBAR DE NAVIGATION
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
            <ul id="menuDeroulantCommun" class="menu-deroulant-commune"">
              <li class=" auteur">
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
            <ul id="menuDeroulantPerso" class="menu-deroulant-perso"  style="display: block;>
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
            <a href="./creationEbook.php">
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
              <div style="text-align: center;">
                <h4>Convertir EPUB en PDF</h4>
                
                <form action="../fonctions_php/convert_epub_en_pdf.php" method="post" enctype="multipart/form-data" id="conversionFormPDF" style="display: inline-block;">
                    <label for="epubFile">Sélectionnez le fichier EPUB à convertir :</label>
                    <input type="file" name="epubFile" id="epubFile" accept=".epub" required>
                    <br>
                    <button type="button" onclick="convertToPDF()">Convertir en .pdf</button>
                </form>

                <h4>Convertir PDF en EPUB</h4>
                
                <form action="../fonctions_php/convert_pdf_en_epub.php" method="post" enctype="multipart/form-data" style="display: inline-block; ">
                    <label for="pdfFile">Sélectionnez le fichier PDF à convertir :</label>
                    <input type="file" name="pdfFile" id="pdfFile" accept=".pdf" required>
                    <br>
                    <input type="submit" value="Convertir en .epub">
                </form>

                <div id="conversionStatus"></div>
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

  <script>
    function convertToPDF() {
        // Créez une nouvelle instance de l'objet FormData pour récupérer les données du formulaire
        var formData = new FormData(document.getElementById('conversionFormPDF'));

        // Créez une nouvelle instance de l'objet XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Configurez la requête
        xhr.open("POST", "../fonctions_php/convert_epub_en_pdf.php", true);

        // Gérez l'événement de fin de requête
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Gérez la réponse du serveur ici
                console.log(xhr.responseText);

                // Affichez le message de conversion dans la div
                document.getElementById("conversionStatus").innerHTML = xhr.responseText;
            } else {
                // Gérez les erreurs ici
                console.error("Une erreur est survenue lors de la requête.");
            }
        };

        // Envoyez la requête avec les données du formulaire
        xhr.send(formData);
    }
  </script>


</body>

</html>