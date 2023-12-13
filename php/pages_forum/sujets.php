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

if (!isset($_SESSION["username"])) {
    header("Location: ../pages_cnx/login.php");
    exit();
}






// Récupérer l'ID du sujet
$idforum = $_GET['idforum'];

// Récupérer le titre du sujet
$nomForum = "";
$forumResult = $conn->query("SELECT nom FROM forum WHERE id = $idforum");

if ($forumResult && $forumResult->num_rows > 0) {
    $forumRow = $forumResult->fetch_assoc();
    $nomForum = $forumRow['nom'];

}
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
</head>


<body>
    <section id="container">
        <!-- **********************************************************************************************************************************************************
          TOP BAR CONTENT & NOTIFICATIONS
          *********************************************************************************************************************************************************** -->
        <!--header start-->
        <header class="header black-bg">
            <!--logo start-->
            <a href="../index.php" class="logo"><b><span>BOOK WAVES /
                        <?php echo ($nomForum);
                        echo isset($_SESSION['username']) ? ' / ' . $_SESSION['username'] : ''; ?>
                    </span></b></a>
            <!--logo end-->
            <div class="nav notify-row text-center" id="top_menu">
                <!--  Categories start -->
                <ul class="nav top-menu">
                    <!-- Ajout Livre Boutton start -->
                    <li id="header_ajout_livre_bar" class="dropdown">

                    </li>
                </ul>
            </div>
            <div class="top-menu">
                <ul class="nav pull-right top-menu">
                    <?php if (isset($_SESSION['username'])): ?>
                        <!-- Utilisateur connecté -->
                        <li><a class="logout"
                                href="../pages_cnx/logout.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Se
                                Déconnecter</a></li>
                        </li>
                    <?php else: ?>
                        <!-- Utilisateur non connecté -->
                        <li><a class="logout" href="../pages_cnx/login.php">Se Connecter</a></li>
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
                    <li class="sub-menu">
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
                        <a href="../pages_autres/livresRecherche.php">
                            <i class="fa fa-globe"></i>
                            <span>Recherche d'Ebook</span>
                        </a>
                    </li>
                    <li class="sub-menu">
                        <a href="../pages_forum/forum.php" class="active">
                            <i class="fa fa-rectangle-list"></i>
                            <span>Forums</span>
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
                <div class="forum">

                    <ul class="sujet-list">
                        <?php
                        // Récupérer l'ID du forum depuis la variable $idforum
                        $idForumActuel = isset($idforum) ? $idforum : null;

                        // Vérifier si l'ID du forum est valide
                        if ($idForumActuel === null || !is_numeric($idForumActuel)) {
                            die("ID de forum non valide.");
                        }

                        $result = $conn->query("SELECT sujet.id, sujet.nom AS titre, sujet.idpersonne, sujet.idforum, users.username AS nom_auteur, sujet.date_dernier_message
                        FROM sujet
                        JOIN users ON sujet.idpersonne = users.id
                        WHERE sujet.idforum = $idforum");

                        if ($result === false) {
                            die("Erreur dans la requête SQL : " . $conn->error);
                        }

                        if ($idForumActuel != 1) {
                            echo "<li class='sujet'>";
                            echo "<form action='traitementAjoutSujet.php' method='post'>";
                            echo "<input type='text' name='nouveauSujet' placeholder='Nouveau sujet' required>";
                            echo "<input type='hidden' name='idforum' value='$idForumActuel'>";
                            echo "<button type='submit'>Créer</button>";
                            echo "</form>";
                            echo "</li>";
                        }

                        // Vérifier si des résultats ont été renvoyés
                        if ($result->num_rows > 0) {
                            if ($_SESSION["idprofil"] != 4) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<li class='sujet'>";
                                    echo "<a href='messages.php?idsujet=" . $row['id'] . "'>";
                                    echo "<h2>" . $row['titre'] . "</h2>";
                                    echo "<p>Auteur: " . $row['nom_auteur'] . "</p>";
                                    echo "</a>";
                                    echo "</li>";
                                }
                            } else {
                                // Afficher le formulaire d'ajout de sujet en premier
                                echo "<li class='sujet'>";
                                echo "<form action='traitementAjoutSujet.php' method='post'>";
                                echo "<input type='text' name='nouveauSujet' placeholder='Nouveau sujet' required>";
                                // Ajoutez un champ caché pour stocker l'ID du forum
                                echo "<input type='hidden' name='idforum' value='$idForumActuel'>";
                                echo "<button type='submit'>Créer</button>";
                                echo "</form>";
                                echo "</li>";

                                // Afficher les autres sujets
                                while ($row = $result->fetch_assoc()) {
                                    echo "<li class='sujet'>";
                                    echo "<a href='messages.php?idsujet=" . $row['id'] . "'>";
                                    echo "<h2>" . $row['titre'] . "</h2>";
                                    echo "<p>Auteur: " . $row['nom_auteur'] . "</p>";
                                    echo "</a>";
                                    echo "</li>";
                                }
                            }
                        } else {
                            // S'il n'y a aucun sujet, afficher uniquement le formulaire
                            echo "<li class='sujet'>";
                            echo "<form action='traitementAjoutSujet.php' method='post'>";
                            echo "<input type='text' name='nouveauSujet' placeholder='Nouveau sujet' required>";
                            // Ajoutez un champ caché pour stocker l'ID du forum
                            echo "<input type='hidden' name='idforum' value='$idForumActuel'>";
                            echo "<button type='submit'>Créer</button>";
                            echo "</form>";
                            echo "</li>";
                            echo "Aucun sujet trouvé pour le forum actuel.";
                        }

                        ?>
                    </ul>

                </div>
                </div>

            </section>
        </section>
        <!--main content end-->


        <script>

        </script>

        <!-- js placed at the end of the document so the pages load faster -->
        <script src="../lib/jquery/jquery.min.js"></script>

        <script src="../lib/bootstrap/js/bootstrap.min.js"></script>


</body>

</html>