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

    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="main-chart">
            <!--CUSTOM CHART START -->
            <div class="border-head">
              <h3>BIBLIOTHEQUE COMMUNE</h3>
              <select id="toc"></select>
              <div id="viewer" class="spreads"></div>


              <script>
                var params =
                  URLSearchParams &&
                  new URLSearchParams(document.location.search.substring(1));
                var url =
                  params && params.get("url") && decodeURIComponent(params.get("url"));
                var currentSectionIndex =
                  params && params.get("loc") ? params.get("loc") : undefined;

                // Load the opf
                var lienEpub = '<?php echo $lienepub; ?>'; // Récupération du lien du livre depuis PHP
                var book = ePub(url || "../lib/Librairy/" + lienEpub + "/" + lienEpub + ".epub");
                var rendition = book.renderTo("viewer", {
                  width: "100%",
                  height: "100%",
                  spread: "always",
                });

                rendition.display(currentSectionIndex);

                book.ready.then(() => {
                  var next = document.getElementById("next");

                  next.addEventListener(
                    "click",
                    function (e) {
                      book.package.metadata.direction === "rtl"
                        ? rendition.prev()
                        : rendition.next();
                      e.preventDefault();
                    },
                    false
                  );

                  var prev = document.getElementById("prev");
                  prev.addEventListener(
                    "click",
                    function (e) {
                      book.package.metadata.direction === "rtl"
                        ? rendition.next()
                        : rendition.prev();
                      e.preventDefault();
                    },
                    false
                  );

                  var keyListener = function (e) {
                    // Left Key
                    if ((e.keyCode || e.which) == 37) {
                      book.package.metadata.direction === "rtl"
                        ? rendition.next()
                        : rendition.prev();
                    }

                    // Right Key
                    if ((e.keyCode || e.which) == 39) {
                      book.package.metadata.direction === "rtl"
                        ? rendition.prev()
                        : rendition.next();
                    }
                  };

                  rendition.on("keyup", keyListener);
                  document.addEventListener("keyup", keyListener, false);
                });

                var title = document.getElementById("title");

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

                rendition.on("layout", function (layout) {
                  let viewer = document.getElementById("viewer");

                  if (layout.spread) {
                    viewer.classList.remove("single");
                  } else {
                    viewer.classList.add("single");
                  }
                });

                window.addEventListener("unload", function () {
                  console.log("unloading");
                  this.book.destroy();
                });

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