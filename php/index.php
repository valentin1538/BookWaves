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
  if(!isset($_SESSION["username"])){
    header("Location: ./pages_cnx/login.php");
    exit(); 
  }

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
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--external css-->
    <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/cf0cc41982.js" crossorigin="anonymous"></script>
</head>


<body>
<section id="container">
    <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
    <!--header start-->
    <header class="header black-bg">
      <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
      </div>
      <!--logo start-->
      <a href="index.php" class="logo"><b><?php echo $_SESSION['username']; ?></span></b></a>
      <!--logo end-->
      <div class="nav notify-row" id="top_menu">
        <!--  Categories start -->
        <ul class="nav top-menu">
          <!-- Ajout Livre Boutton start -->
         <!-- <li id="header_ajout_livre_bar" class="dropdown">
            
            <a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">
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
          <li id="header_Editer_metadonnees_bar" class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">
              Editer les metadonnées
              <i class="fa-solid fa-file-pen"></i>
                </a>
                <ul class="dropdown-menu extended notification">
                <div class="notify-arrow notify-arrow-green"></div>
                <li>
                  <a href="index.html#">
                    <span class="label label-success"><i class="fa fa-pen"></i></span>
                    Editer les métadonnées d'un livre
                    </a>
                </li>
              </ul>
          </li>
          <li id="header_convertir_livre_bar" class="dropdown">
          <a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">
              Convertir
              <i class="fa-solid fa-repeat"></i>
                </a>
              <ul class="dropdown-menu extended notification">
                <div class="notify-arrow notify-arrow-green"></div>
                <li>
                  <a href="index.html#">
                    <span class="label label-warning"><i class="fa fa-arrow-right"></i></span>
                    Convertir le format d'un livre
                    </a>
                </li>
              </ul>
          </li>
          <li id="header_convertir_livre_bar" class="dropdown">
          <a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">
            Visualiser
              <i class="fa-solid fa-eye"></i>
                </a>
              <ul class="dropdown-menu extended notification">
                <div class="notify-arrow notify-arrow-green"></div>
                <li>
                  <a href="index.html#">
                    <span class="label label-success"><i class="fa fa-expand"></i></span>
                    Visualiser un livre
                    </a>
                </li>
              </ul>
          </li>
          <li id="header_convertir_livre_bar" class="dropdown">
          <a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">
            Supprimer
              <i class="fa-solid fa-trash"></i>
                </a>
              <ul class="dropdown-menu extended notification">
                <div class="notify-arrow notify-arrow-green"></div>
                <li>
                  <a href="index.html#">
                    <span class="label label-danger"><i class="fa fa-x"></i></span>
                    Supprimer un livre
                    </a>
                </li>
              </ul>
          </li>
          <li id="header_convertir_livre_bar" class="dropdown">
          <a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">
            Favoris
              <i class="fa-solid fa-bookmark"></i>
                </a>
              <ul class="dropdown-menu extended notification">
                <div class="notify-arrow notify-arrow-green"></div>
                <li>
                  <a href="index.html#">
                    <span class="label label-success"><i class="fa fa-plus"></i></span>
                    Ajouter un livre en favoris
                    </a>
                </li>
                <li>
                  <a href="index.html#">
                    <span class="label label-warning"><i class="fa fa-magnifying-glass"></i></span>
                    Afficher les favoris
                    </a>
                </li>
              </ul>
          </li>
          <li id="header_convertir_livre_bar" class="dropdown">
          <a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">
            Enregistrer sous
              <i class="fa-solid fa-floppy-disk"></i>
                </a>
              <ul class="dropdown-menu extended notification">
                <div class="notify-arrow notify-arrow-green"></div>
                <li>
                  <a href="index.html#">
                    <span class="label label-success"><i class="fa fa-file-export"></i></span>
                    Enregistrer sous...
                    </a>
                </li>
              </ul>
          </li>
          <li id="header_convertir_livre_bar" class="dropdown">
          <a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">
            Recupération des actualités
              <i class="fa-solid fa-newspaper"></i>
                </a>
              <ul class="dropdown-menu extended notification">
                <div class="notify-arrow notify-arrow-green"></div>
                <li>
                  <a href="index.html#">
                    <span class="label label-danger"><i class="fa fa-calendar"></i></span>
                    Planifier le dl des actualités
                    </a>
                </li>
              </ul>
          </li>
        </ul>
         notification end -->
      </div>
      <div class="top-menu">
        <ul class="nav pull-right top-menu">
          <li><a class="logout" href="./pages/logout.php">Déconnexion</a></li>
        </ul>
      </div>
    </header>
    <!--header end-->
    <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <aside>
      <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
        <li class="Formats">
            <a href="index.php">
              <i class="fa fa-book"></i>
              <span>Bibliothèque</span>
              </a>
          </li>
          <li class="Livres">
            <a href="./pages/livres.php">
              <i class="fa fa-book-open"></i>
              <span>Livres</span>
              </a>
          </li>
          <li class="auteur">
            <a href="index.php">
              <i class="fa fa-user-tie"></i>
              <span>Auteur</span>
              </a>
          </li>
          <li class="editeur">
            <a href="index.php">
              <i class="fa fa-feather"></i>
              <span>Editeur</span>
              </a>
          </li>
          <li class="Genres">
            <a href="index.php">
              <i class="fa fa-tags"></i>
              <span>Genres</span>
              </a>
          </li>
        </ul>
        <!-- sidebar menu end-->
      </div>
    </aside>
    <!--sidebar end-->

   <!--main content start-->
   <section id="main-content">
      <section class="wrapper">
        <center> Coucou </center>
      </section>
    </section>
    <!--main content end-->

  
  <script>
    const bookList = document.getElementById('book-list');
    const addBookButton = document.getElementById('add-book');
    const fileInput = document.getElementById('file-input');

    const bookDetails = document.getElementById('book-details');
    const titleInput = document.getElementById('title');
    const authorInput = document.getElementById('author');
    const saveDetailsButton = document.getElementById('save-details');
    const closeDetailsButton = document.getElementById('close-details');

    addBookButton.addEventListener('click', () => {
      fileInput.click();
    });

    fileInput.addEventListener('change', (event) => {
      const files = event.target.files;
      if (files.length > 0) {
        const newBookItem = document.createElement('li');
        newBookItem.classList.add('book-item');

        newBookItem.innerHTML = `
          <div class="col-md-2 col-sm-5 mb">
            <div class="darkblue-panel pn">
              <div class="darkblue-header">
                <p style="color : white;">${files[0].name}</p>
              </div>
              <p>Auteur : Aucun</p>
              <footer>
                <div class="pull-left">
                  <h5><i class="fa fa-hdd-o"></i></h5>
                </div>
                <div class="pull-right">
                  <h5>Format : Ebup</h5>
                </div>
              </footer>
            </div>
          </div>
        `;

        bookList.appendChild(newBookItem);
      }
    });

    bookList.addEventListener('click', (event) => {
      if (event.target.classList.contains('edit-button')) {
        bookDetails.style.display = 'block';
        const bookItem = event.target.closest('.book-item');
        const titleElement = bookItem.querySelector('h3');
        const authorElement = bookItem.querySelector('p:nth-of-type(2)');
        titleInput.value = titleElement.textContent;
        authorInput.value = authorElement.textContent.split(':')[1].trim();
      }

      if (event.target.classList.contains('delete-button')) {
        const bookItem = event.target.closest('.book-item');
        bookList.removeChild(bookItem);
      }
    });

    saveDetailsButton.addEventListener('click', () => {
      const selectedBook = document.querySelector('.book-item .edit-button:focus');
      if (selectedBook) {
        const titleElement = selectedBook.querySelector('h3');
        const authorElement = selectedBook.querySelector('p:nth-of-type(2)');
        titleElement.textContent = titleInput.value;
        authorElement.textContent = `Auteur : ${authorInput.value}`;
        bookDetails.style.display = 'none';
      }
    });

    closeDetailsButton.addEventListener('click', () => {
      bookDetails.style.display = 'none';
    });
  </script>

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