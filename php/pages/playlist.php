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
      <a href="../index.php" class="logo"><b><span>BOOK WAVES <?php echo isset($_SESSION['username']) ? ' / ' . $_SESSION['username'] : ''; ?></span></b></a>
      <!--logo end-->
        <!--  Categories start -->
      <h1 class = "titre-page">Playlist</h1>
        <ul class="nav pull-right top-menu">
        <?php if (isset($_SESSION['username'])): ?>
          <!-- Utilisateur connecté -->
          <li><a class="logout" href="../pages_cnx/logout.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Déconnexion</a></li>
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
      <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
        <li class="livres">
            <a href="../index.php">
              <i class="fa fa-book"></i>
              <span>Livres</span>
              </a>
          </li>
          <li class="playlist">
            <a href="playlist.php">
              <i class="fa fa-book-open"></i>
              <span>Playlist</span>
              </a>
          </li>
          <li class="auteur">
            <a href="auteur.php">
              <i class="fa fa-user-tie"></i>
              <span>Auteur</span>
              </a>
          </li>
          <li class="editeur">
            <a href="indediteurex.php">
              <i class="fa fa-feather"></i>
              <span>Editeur</span>
              </a>
          </li>
          <li class="genres">
            <a href="genres.php">
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