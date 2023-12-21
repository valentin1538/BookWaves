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
$sql = "SELECT livre.id, livre.nom, livre.infos, 
               auteur.nom as nom_auteur, 
               editeur.nom as nom_editeur, 
               genre.nom as nom_genre, 
               langue.nom as nom_langue, 
               collection.nom as nom_collection
        FROM livre
        INNER JOIN auteur ON livre.idauteur = auteur.id
        INNER JOIN editeur ON livre.idediteur = editeur.id
        INNER JOIN genre ON livre.idgenre = genre.id
        INNER JOIN langue ON livre.idlangue = langue.id
        INNER JOIN collection ON livre.idcollection = collection.id"; 


$result = $conn->query($sql);
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var editeurLinks = document.querySelectorAll('.editeur-link');
    var collectionsList = document.getElementById('collections-list');
    var livresList = document.getElementById('livres-list');
    var livresCollection = document.getElementById('livres-collection');

    editeurLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            var editeurId = this.getAttribute('data-editeur-id');
            chargerCollectionsParediteur(editeurId);
            chargerLivresParediteur(editeurId);
        });
    });

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('collection-link')) {
            var collectionId = event.target.getAttribute('data-collection-id');
            chargerLivresParCollection(collectionId);
        }
    });
});

</script>



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
                            <a href="./editeurs_commune.php" class="active">
                                <i class="fa fa-feather"></i>
                                <span>Editeurs</span>
                            </a>
                        </li>
                        <li class="Genres">
                            <a href="./genres_commune.php">
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
              <h3>EDITEURS</h3>
              <form class="barre-recherche" action="" method="GET">
                    <input type="text" name="recherche" placeholder="Rechercher un editeur" value="">
               
                </form>
              <div class="container">
    <?php
    $queryEditeurs = "SELECT id, nom FROM editeur";
    $resultEditeurs = $conn->query($queryEditeurs);

    if ($resultEditeurs && $resultEditeurs->num_rows > 0) {
        while ($rowEditeur = $resultEditeurs->fetch_assoc()) {
            $editeurId = $rowEditeur['id'];
            $editeurName = $rowEditeur['nom'];

            echo '<div class="book">';
            echo "<a href='#' class='editeur-link' data-editeur-id='$editeurId'>$editeurName</a>";
            echo '</div>';
        }
    } else {
        echo "Aucun éditeur trouvé dans la base de données.";
    }
    ?>
</div>

<!-- Affichage des collections -->
<div class="collections-list" id="collections-list">
    <!-- Cette section sera mise à jour dynamiquement par JavaScript -->
</div>

<div> <br> </div>
<!-- Affichage des livres de la collection sélectionnée -->
<div class="livres-collection" id="livres-collection">
    <!-- Cette section sera mise à jour dynamiquement par JavaScript -->
</div>
                                                                                                                                                                            
<!-- Affichage des livres de l'éditeur sélectionné -->
<div class="livres-list" id="livres-list">
    <!-- Cette section sera mise à jour dynamiquement par JavaScript -->
</div>



<script>
      document.addEventListener('DOMContentLoaded', function () {
            var rechercheInput = document.querySelector('input[name="recherche"]');
            var editeurs = document.querySelectorAll('.book');

            rechercheInput.addEventListener('input', function () {
                var rechercheValue = this.value.toLowerCase();

                editeurs.forEach(function (editeur) {
                    var nomEditeur = editeur.querySelector('.editeur-link').textContent.toLowerCase();
                    if (nomEditeur.includes(rechercheValue)) {
                        editeur.style.display = 'block';
                    } else {
                        editeur.style.display = 'none';
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
    var editeurLinks = document.querySelectorAll('.editeur-link');
    var collectionsList = document.getElementById('collections-list');
    var livresList = document.getElementById('livres-list');
    var livresCollection = document.getElementById('livres-collection');

    editeurLinks.forEach(function (link) {
   link.addEventListener('click', function (event) {
       event.preventDefault();
       var editeurId = this.getAttribute('data-editeur-id');
       chargerCollectionsParEditeur(editeurId); 
       chargerLivresParediteur(editeurId); 
   });
});


    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('collection-link')) {
            var collectionId = event.target.getAttribute('data-collection-id');

            chargerLivresParCollection(collectionId);
        }
    });

    function chargerCollectionsParEditeur(editeurId) {
       var xhr = new XMLHttpRequest();
       xhr.open('GET', 'charger-collections-par-editeur.php?editeurId=' + editeurId, true);

       xhr.onload = function () {
           if (xhr.status >= 200 && xhr.status < 400) {
               collectionsList.innerHTML = xhr.responseText;
           } else {
               console.error('La requête a échoué.');
           }
       };

       xhr.onerror = function () {
           console.error('Erreur réseau.');
       };

       xhr.send();
   }


   document.addEventListener('DOMContentLoaded', function () {
    var editeurLinks = document.querySelectorAll('.editeur-link');
    var livresList = document.getElementById('livres-list');

    editeurLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            var editeurId = this.getAttribute('data-editeur-id');
            chargerLivresParEditeur(editeurId);
        });
    });

    function chargerLivresParEditeur(editeurId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'charger-livres-par-editeur.php?editeurId=' + editeurId, true);

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 400) {
                livresList.innerHTML = xhr.responseText;
            } else {
                console.error('La requête a échoué avec le statut :', xhr.status);
            }
        };

        xhr.onerror = function () {
            console.error('Erreur réseau.');
        };

        xhr.send();
    }
});


    document.addEventListener('DOMContentLoaded', function () {
        var editeurLinks = document.querySelectorAll('.editeur-link');

        editeurLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                var editeurId = this.getAttribute('data-editeur-id');
                chargerLivresParediteur(editeurId);
            });
        });
    });

   function chargerLivresParCollection(collectionId) {
       var xhr = new XMLHttpRequest();
       xhr.open('GET', 'charger-livres-par-collection.php?collectionId=' + collectionId, true);

       xhr.onload = function () {
           if (xhr.status >= 200 && xhr.status < 400) {
               livresCollection.innerHTML = xhr.responseText;
           } else {
               console.error('La requête a échoué avec le statut :', xhr.status);
           }
       };

       xhr.onerror = function () {
           console.error('Erreur réseau.');
       };

       xhr.send();
   }

   

});




</script>

          </div>
          <!-- /col-lg-3 -->
        </div>
        <!-- /row -->
        </div>
      </section>
    </section>
      <!--main content end-->



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