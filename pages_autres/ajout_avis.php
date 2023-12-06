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
// Récupération de l'ID du Livre depuis l'URL
$idLivre = isset($_GET['id']) ? $_GET['id'] : 'ID du Livre Exemple';

// Vérification de la session utilisateur
session_start();
$idPersonne = isset($_SESSION['id']) ? $_SESSION['id'] : null;

// Vérification si l'utilisateur est connecté
if (!$idPersonne) {
  echo "<div class='error-message'>Vous devez être connecté pour ajouter un avis.</div>";
} else {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $idLivre = isset($_POST['idLivre']) ? $_POST['idLivre'] : 'ID du Livre Exemple';
      $commentaire = isset($_POST['avis']) ? htmlspecialchars($_POST['avis']) : '';
      $note = isset($_POST['note']) ? intval($_POST['note']) : 0;

      if ($idLivre !== 'ID du Livre Exemple' && $commentaire !== '' && $note > 0 && $note <= 5) {
          $sql = "INSERT INTO avis (idpersonne, idlivre, commentaire, note) VALUES (?, ?, ?, ?)";
          $stmt = $conn->prepare($sql);

          // Liaison des valeurs et exécution de la requête
          $stmt->bind_param("iisd", $idPersonne, $idLivre, $commentaire, $note);
          if ($stmt->execute()) {
            // Stocker le message de succès dans une session
            session_start();
            $_SESSION['success_message'] = "Avis ajouté avec succès!";
            
            // Redirection vers la page index.php après l'insertion
            header("Location: ../index.php");
            exit();
        } else {
            echo "<div class='error-message'>Vous ne pouvez laisser qu'un avis par livre.</div>";
        }
          }
      } else {
          echo "Données invalides pour l'ajout de l'avis.";
      }
  }
?>

<style>
    .error-message {
        background-color: #ffcccc; /* Fond rouge clair */
        padding: 10px; /* Espacement intérieur pour plus de lisibilité */
        margin-bottom: 10px; /* Espacement avec les autres éléments */
        border: 1px solid #ff0000; /* Bordure rouge */
        color: #ff0000; /* Texte rouge */
    }
</style>

<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
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
            <ul id="menuDeroulantCommun" class="menu-deroulant-commune" style="display: block;>
                          <li class=" auteur">
              <a href="../index.php" class="active">
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

    <!DOCTYPE html>
<html>
<body>

  <!-- Contenu principal -->
  <div class="container">
    <!-- ... Votre contenu actuel ... -->

    <!-- Formulaire d'ajout d'avis -->
    <h2>Ajouter un Avis</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label for="nomLivre">Nom du Livre :</label>
            <input type="text" class="form-control" id="nomLivre" name="nomLivre" value="Nom du Livre Exemple" readonly>
            <!-- Champ caché pour stocker l'ID du livre -->
            <?php
            // Récupération de l'ID du livre depuis l'URL
            $idLivre = isset($_GET['id']) ? $_GET['id'] : 'ID du Livre Exemple';
            ?>
            <input type="hidden" id="idLivre" name="idLivre" value="<?php echo $idLivre; ?>">
        </div>
        <div class="form-group">
            <label for="avis">Votre Avis :</label>
            <textarea class="form-control" id="avis" name="avis" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="note">Note :</label>
            <input type="number" class="form-control" id="note" name="note" min="1" max="5" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter l'Avis</button>
    </form>

    <!-- Vos autres éléments, scripts, etc. -->

</body>
</html>