<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$servername = "localhost"; // Remplacez par le nom de votre serveur de base de données
$username = "root"; // Remplacez par votre nom d'utilisateur de base de données
$password = ""; // Remplacez par votre mot de passe de base de données
$database = "Biblio"; // Remplacez par le nom de votre base de données

// Créez la connexion à la base de données
$conn = new mysqli($servername, $username, $password, $database);

$userQuery = "SELECT id, username FROM users";
$userResult = $conn->query($userQuery);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}
 // Initialiser la session
session_start();
  // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if(!isset($_SESSION["username"])){
    header("Location: ../pages_cnx/login.php");
    exit(); 
}
if(($_SESSION["idprofil"])!=4){
    header("Location: ../pages_cnx/login.php");
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookWaves / Gestion</title>
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
          <header class="header black-bg">
            <!--logo start-->
            <a href="../index.php" class="logo"><b><span>BOOK WAVES <?php echo isset($_SESSION['username']) ? ' / ' . $_SESSION['username'] : ''; ?></span></b></a>
            <!--logo end-->
            <h2 style="color: white;">GESTION</h2>
            <div class="top-menu">
              <ul class="nav pull-right top-menu">
                  <?php if (isset($_SESSION['username'])): ?>
                    <!-- Utilisateur connecté -->
                    <li><a class="logout" href="./pages_cnx/logout.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Déconnexion</a></li>
                <?php else: ?>
                    <!-- Utilisateur non connecté -->
                    <li><a class="logout" href="./pages_cnx/login.php">Se Connecter</a></li>
                <?php endif; ?>
            </ul>
        </div>
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
                                <a href="./index_admin.php">
                                    <i class="fa fa-user-tie"></i>
                                    <span>Livres</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="./gestion_users">
                            <i class="fa fa-users"></i>
                            <span>Gestion Utilisateurs</span>
                        </a>

                        <a href="./gestion_editeur">
                            <i class="fa fa-users"></i>
                            <span>Gestion Editeur</span>
                        </a>
                    </li>
                </ul>
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
                        <h3>EDITEURS</h3>
                        <div class="container-ges" id="form-list">
                            <?php
                            require('../pages_cnx/config.php');

                            if (isset($_REQUEST['nomEditeur'])) {
                            // récupérer le nom de l'éditeur et supprimer les antislashes ajoutés par le formulaire
                                $nomEditeur = stripslashes($_REQUEST['nomEditeur']);
                                $nomEditeur = mysqli_real_escape_string($conn, $nomEditeur);

                            // Vérifier si l'éditeur existe déjà
                                $check_query = "SELECT * FROM `editeur` WHERE nom = '$nomEditeur'";
                                $check_result = mysqli_query($conn, $check_query);

                                if (mysqli_num_rows($check_result) > 0) {
                                // L'éditeur existe déjà, afficher un message d'erreur
                                    echo "L'éditeur existe déjà.";
                                } else {
                                // L'éditeur n'existe pas, procéder à l'insertion dans la base de données
                                    $query = "INSERT INTO `editeur` (nom) VALUES ('$nomEditeur')";

                                // Exécuter la requête sur la base de données
                                    $res = mysqli_query($conn, $query);

                                    if ($res) {
                                        echo "<div id='containerh'>
                                        <form class='box' action='' method='post' name='login'>
                                        <h3>Vous avez ajouté cet éditeur avec succès.</h3>
                                        </form>
                                        </div>";
                                    }
                                }
                            } else {
                            // Reste du code pour le formulaire d'ajout d'éditeur
                            // ...
                            }
                            ?>
                        </div>

                        <div class="ges-item">
                            <form class="box" action="" method="post" name="addEditeurForm">

                                <h1 style='color:#4ecdc4'> Ajout d'éditeur </h1>
                                <input type="text" class="box-input" name="nomEditeur" placeholder="Nom de l'éditeur" required />
                                <input type="submit" name="submitEditeur" value="Confirmer" style='color:black' class="box-button" />
                            </form>
                        </div>

                        <!-- Ajout d'une section pour la suppression d'éditeur -->
                        <div class="ges-item">
                            <form class="box" action="" method="post" name="deleteEditeurForm">
                                <h1 style='color:red'> Suppression d'Éditeur </h1> 
                                <!-- Liste déroulante pour sélectionner l'éditeur à supprimer -->
                                <select name="editeurToDelete">
                                    <?php
                                // Afficher la liste des éditeurs
                                    $editeurResult = $conn->query("SELECT id, nom FROM editeur");
                                    while ($row = $editeurResult->fetch_assoc()) {
                                        echo "<option value=\"{$row['id']}\">{$row['nom']}</option>";
                                    }
                                    ?>
                                </select>
                                <!-- Bouton de confirmation pour supprimer l'éditeur -->
                                <input type="submit" name="deleteEditeurSubmit" value="Supprimer" style='color:black' class="box-button" />
                            </form>
                        </div>

                        <!-- Formulaire pour attribuer un livre à un éditeur -->
                        <div class="ges-item">
                            <form method="post">
                                <<h1 style='color:purple'> Attribuer un livre à un éditeur : </h1> 
                                <select name="livre">
                                    <option value="">Sélectionnez un livre</option>
                                    <?php
                // Connexion à la base de données avec MySQLi
                                    $mysqli = new mysqli("localhost", "root", "", "Biblio");

                                    if ($mysqli->connect_error) {
                                        die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
                                    }

                $livres = array(); // Créez un tableau pour stocker la liste des livres

                $queryLivres = "SELECT id, nom, idediteur FROM livre";
                $resultLivres = $mysqli->query($queryLivres);

                if ($resultLivres) {
                    while ($row = $resultLivres->fetch_assoc()) {
                        $livres[] = $row; // Ajoutez chaque livre au tableau
                    }
                } else {
                    echo "Erreur lors de la récupération des livres : " . $mysqli->error;
                }

                $resultLivres->free(); // Libérer le résultat de la requête

                foreach ($livres as $livre) {
                    echo "<option value='{$livre['id']}'>{$livre['nom']}</option>";
                }
                ?>
            </select>
            <select name="editeur">
                <option value="">Sélectionnez un éditeur</option>
                <?php
                // Connexion à la base de données avec MySQLi
                $mysqli = new mysqli("localhost", "root", "", "Biblio");

                if ($mysqli->connect_error) {
                    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
                }

                $editeurs = array(); // Créez un tableau pour stocker la liste des éditeurs

                $queryEditeurs = "SELECT id, nom FROM editeur";
                $resultEditeurs = $mysqli->query($queryEditeurs);

                if ($resultEditeurs) {
                    while ($row = $resultEditeurs->fetch_assoc()) {
                        $editeurs[] = $row; // Ajoutez chaque éditeur au tableau
                    }
                } else {
                    echo "Erreur lors de la récupération des éditeurs : " . $mysqli->error;
                }

                $resultEditeurs->free(); // Libérer le résultat de la requête

                $mysqli->close(); // Fermer la connexion

                foreach ($editeurs as $editeur) {
                    echo "<option value='{$editeur['id']}'>{$editeur['nom']}</option>";
                }
                ?>
            </select>
            <input type="submit" name="assignerLivre" class="button" value="Assigner le livre à l'éditeur">
        </form>


        <?php
        // Gérer l'attribution d'un livre à un éditeur
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["assignerLivre"])) {
            $livreId = $_POST["livre"];
            $editeurId = $_POST["editeur"];

            // Connexion à la base de données avec MySQLi
            $mysqli = new mysqli("localhost", "root", "", "Biblio");

            if ($mysqli->connect_error) {
                die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
            }

            // Requête SQL pour attribuer le livre à l'éditeur
            $query = "UPDATE livre SET idediteur = ? WHERE id = ?";
            $stmt = $mysqli->prepare($query);

            // Liez les paramètres
            $stmt->bind_param("ii", $editeurId, $livreId);

            // Exécutez la requête
            if ($stmt->execute()) {
                echo "Livre attribué à l'éditeur avec succès.";

            } else {
                echo "Erreur lors de l'attribution du livre à l'éditeur : " . $mysqli->error;
            }

            $stmt->close(); // Fermer la requête préparée
            $mysqli->close(); // Fermer la connexion
        }
        ?>

        <?php
                    // Traitement de la suppression d'éditeur
        if (isset($_POST['deleteEditeurSubmit'])) {
            $editeurIdToDelete = $_POST['editeurToDelete'];

                        // Requête pour supprimer l'éditeur sélectionné
            $deleteEditeurQuery = "DELETE FROM editeur WHERE id = '$editeurIdToDelete'";
            $deleteEditeurResult = $conn->query($deleteEditeurQuery);

            if ($deleteEditeurResult) {
                        // Rediriger vers la même page après la suppression
                header("Location: {$_SERVER['PHP_SELF']}");
                exit();
            }    
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

<?php ob_end_flush(); ?>