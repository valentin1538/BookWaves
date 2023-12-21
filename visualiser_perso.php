<!DOCTYPE html>
<html>
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
            <ul id="menuDeroulantCommun" class="menu-deroulant-commune">
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
            <ul id="menuDeroulantPerso" class="menu-deroulant-perso" style="display: block;>
            <li class=" auteur">
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
              <h3>BIBLIOTHEQUE PERSONNELLE</h3>
              <select id="toc"></select>
              <div id="viewer" class="spreads"></div>

              <!-- VISUALISATION D'UN LIVRE (ANTONIN / GAUTHIER / VALENTIEN PERREIRA) -->
              <script>
                // Récupération des paramètres de l'URL
                var params =
                  URLSearchParams &&
                  new URLSearchParams(document.location.search.substring(1));

                // Récupération du lien du livre depuis PHP
                var url =
                  params && params.get("url") && decodeURIComponent(params.get("url"));
                var currentSectionIndex =
                  params && params.get("loc") ? params.get("loc") : undefined;

                // Chargement du livre au format ePub
                var lienEpub = '<?php echo $lienepub; ?>'; // Récupération du lien du livre depuis PHP
                var book = ePub(url || "../lib/Librairy/" + lienEpub + "/" + lienEpub + ".epub");

                // Rendu du livre dans la zone avec l'ID "viewer"
                var rendition = book.renderTo("viewer", {
                  width: "100%",
                  height: "100%",
                  spread: "always",
                });

                // Affichage de la section courante
                rendition.display(currentSectionIndex);

                book.ready.then(() => {
                  var next = document.getElementById("next");// Récupération des éléments pour la navigation dans le livre

                  next.addEventListener(  // Ajout des écouteurs d'événements pour la navigation
                    "click",
                    function (e) {
                      // Navigation vers la page suivante ou précédente du livre
                      book.package.metadata.direction === "rtl"
                        ? rendition.prev()
                        : rendition.next();
                      e.preventDefault();
                    },
                    false
                  );

                  var prev = document.getElementById("prev");// Récupération des éléments pour la navigation dans le livre
                  prev.addEventListener(
                    "click",
                    function (e) {
                      // Navigation vers la page suivante ou précédente du livre
                      book.package.metadata.direction === "rtl"
                        ? rendition.next()
                        : rendition.prev();
                      e.preventDefault();
                    },
                    false
                  );

                  // Gestion des événements liés aux touches du clavier pour la navigation
                  var keyListener = function (e) {

                    // Touche gauche pour aller à la page précédente, touche droite pour aller à la page suivante
                    if ((e.keyCode || e.which) == 37) {
                      book.package.metadata.direction === "rtl"
                        ? rendition.next()
                        : rendition.prev();
                    }

                    // Touche droite
                    if ((e.keyCode || e.which) == 39) {
                      book.package.metadata.direction === "rtl"
                        ? rendition.prev()
                        : rendition.next();
                    }
                  };

                  // Ajout des écouteurs d'événements pour les touches du clavier
                  rendition.on("keyup", keyListener);
                  document.addEventListener("keyup", keyListener, false);
                });

                var title = document.getElementById("title");

                // Gestion de l'affichage des sections du livre
                rendition.on("rendered", function (section) {
                  var current = book.navigation && book.navigation.get(section.href);

                  if (current) {
                    var $select = document.getElementById("toc");
                    var $selected = $select.querySelector("option[selected]");
                    if ($selected) {
                      $selected.removeAttribute("selected");
                    }

                    var $options = $select.querySelectorAll("option");
                    for (var i = 0; i < $options.length; ++i) {
                      let selected = $options[i].getAttribute("ref") === current.href;
                      if (selected) {
                        $options[i].setAttribute("selected", "");
                      }
                    }
                  }
                });

                // Gestion du déplacement dans le livre
                rendition.on("relocated", function (location) {
                  console.log(location);

                  var next =
                    book.package.metadata.direction === "rtl"
                      ? document.getElementById("prev")
                      : document.getElementById("next");
                  var prev =
                    book.package.metadata.direction === "rtl"
                      ? document.getElementById("next")
                      : document.getElementById("prev");

                  if (location.atEnd) {
                    next.style.visibility = "hidden";
                  } else {
                    next.style.visibility = "visible";
                  }

                  if (location.atStart) {
                    prev.style.visibility = "hidden";
                  } else {
                    prev.style.visibility = "visible";
                  }
                });

                // Gestion du changement de mise en page du livre
                rendition.on("layout", function (layout) {
                  let viewer = document.getElementById("viewer");

                  if (layout.spread) {
                    viewer.classList.remove("single");
                  } else {
                    viewer.classList.add("single");
                  }
                });

                // Fermer le livre à la fermeture
                window.addEventListener("unload", function () {
                  console.log("unloading");
                  this.book.destroy();
                });

                // Récupération de la table des matières
                book.loaded.navigation.then(function (toc) {
                  var $select = document.getElementById("toc"),
                    docfrag = document.createDocumentFragment();

                  toc.forEach(function (chapter) {
                    var option = document.createElement("option");
                    option.textContent = chapter.label;
                    option.setAttribute("ref", chapter.href);

                    docfrag.appendChild(option);
                  });

                  $select.appendChild(docfrag);

                  $select.onchange = function () {
                    var index = $select.selectedIndex,
                      url = $select.options[index].getAttribute("ref");
                    rendition.display(url);
                    return false;
                  };
                });
              </script>
            </div>
            <!--custom chart end-->
          </div>
          <!-- /col-lg-3 -->
          <div class="arrow-container">
            <a id="prev" href="#prev" class="arrow">‹</a>
            <a id="next" href="#next" class="arrow">›</a>
          </div>
        </div>
        <!-- /row -->
      </section>
    </section>
    <!--main content end-->
  </section>

</body>

</html>