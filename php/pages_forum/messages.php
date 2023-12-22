<?php
// SOUS PROJET HUGO DAVION

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
// Récupérer l'ID du sujet depuis la requête GET
if (!isset($_SESSION["username"])) {
    header("Location: ../pages_cnx/login.php");
    exit();
}
if (!isset($_GET['idsujet']) || !is_numeric($_GET['idsujet'])) {
    // Rediriger l'utilisateur si l'ID du sujet n'est pas valide
    header("Location: sujets.php");
    exit();
}


// Récupérer l'ID du sujet
$idsujet = $_GET['idsujet'];


// Récupérer le titre du sujet
$titreSujet = "";
$resultSujet = $conn->query("SELECT nom FROM sujet WHERE id = $idsujet");

if ($resultSujet && $resultSujet->num_rows > 0) {
    $rowSujet = $resultSujet->fetch_assoc();
    $titreSujet = $rowSujet['nom'];
}

// Récupérer les messages du sujet
$resultMessages = $conn->query("SELECT message.id, message.contenu, message.date_creation, users.username
                                FROM message
                                JOIN users ON message.idpersonne = users.id
                                WHERE message.idsujet = $idsujet
                                ORDER BY message.date_creation");

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
                        <?php echo ($titreSujet) ?>
                        <?php echo isset($_SESSION['username']) ? ' / ' . $_SESSION['username'] : ''; ?>
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
                        <a href="../pages-autres/creationEbook.php">
                        <i class="fa-solid fa-plus"></i>
                        <span>Créer un livre</span>
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

                    <div class="sujet-list">
                        <?php
                        $conn->set_charset("utf8");

                        // Récupérer les messages du sujet
                        $resultMessages = $conn->query("SELECT message.id, message.contenu, message.date_creation, users.username, message.idpersonne
                                FROM message
                                JOIN users ON message.idpersonne = users.id
                                WHERE message.idsujet = $idsujet
                                ORDER BY message.date_creation");

                        if ($resultMessages === false) {
                            die("Erreur dans la requête SQL : " . $conn->error);
                        }

                        // ... (votre code existant)
                        
                        // Afficher les messages
                        if ($resultMessages->num_rows > 0) {
                            while ($rowMessage = $resultMessages->fetch_assoc()) {
                                echo "<div class='message-container'>";
                                // Récupérer l'ID de l'utilisateur connecté
                        
                                // Récupérer le chemin de la photo de profil depuis la base de données en fonction de l'id de l'utilisateur
                                $idUser = $rowMessage['idpersonne'];
                                $resultUser = $conn->query("SELECT urlpfp, username FROM users WHERE id = $idUser");

                                if ($resultUser && $resultUser->num_rows > 0) {
                                    $rowUser = $resultUser->fetch_assoc();
                                    $urlProfil = !empty($rowUser['urlpfp']) ? $rowUser['urlpfp'] : '../images/PP/defaut.jpg';

                                    // Afficher la photo de profil à gauche du pseudo
                                    echo "<div class='profile-info'>";
                                    echo "<img src='" . $urlProfil . "' alt='Photo de profil'>";
                                    echo "</div>";

                                    // Afficher les informations de l'utilisateur (nom et date)
                                    echo "<div class='user-info'>";
                                    echo "<b  style='color: #4ecdc4; font-size: 18px; '> " . $rowUser['username'] . "</b>";
                                    echo "<p style='font-size: 12px;'>" . $rowMessage['date_creation'] . "</p>";
                                    echo "</div>";
                                } else {
                                    echo "Erreur lors de la récupération des informations de l'utilisateur.";
                                }

                                // Afficher le contenu du message
                                echo "<div class='message-content'>";
                                echo "<b>" . nl2br(htmlspecialchars($rowMessage['contenu'])) . "</b>";
                                echo "</div>";
                                // Récupérer l'ID de l'utilisateur connecté
                                $userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;


                                // Vérifier si l'ID de l'utilisateur connecté correspond à l'ID de l'auteur du message
                                if ($userId == $rowMessage['idpersonne']) {
                                    echo "<br>";
                                    echo "<form  action='traitementSuppressionMessage.php' method='post'>";
                                    echo "<input type='hidden' name='idmessage' value='" . $rowMessage['id'] . "'>";
                                    echo "<input type='hidden' name='idsujet' value='" . $idsujet . "'>";
                                    echo "<input class='bleh' type='submit' value='Supprimer le message'>";
                                    echo "</form>";
                                } else {
                                    if ($_SESSION['idprofil'] == 4) {
                                        echo "<br>";
                                        echo "<form  action='traitementSuppressionMessage.php' method='post'>";
                                        echo "<input type='hidden' name='idmessage' value='" . $rowMessage['id'] . "'>";
                                        echo "<input type='hidden' name='idsujet' value='" . $idsujet . "'>";
                                        echo "<input class='bleh' type='submit' value='Supprimer le message'>";
                                        echo "</form>";
                                    }
                                }




                                echo "</div>";

                            }
                        } else {
                            echo "<p>Aucun message trouvé pour ce sujet.</p>";
                        }

                        ?>

                        <form id="ajoutMessageForm" action="traitementAjoutMessage.php" method="post">
                            <h3>Ajouter un message</h3>
                            <input type="hidden" name="idsujet" value="<?php echo $idsujet; ?>">
                            <textarea name="contenu" placeholder="Écrivez votre message ici" required></textarea>
                            <br>
                            <input type="submit" value="Envoyer le message">
                        </form>

                        <!-- Liste des messages -->
                        <div class="messages-list">
                            <?php
                            // Afficher les messages
                            if ($resultMessages->num_rows > 0) {
                                while ($rowMessage = $resultMessages->fetch_assoc()) {
                                    echo "<div class='message'>";
                                    echo "<p><strong>" . $rowMessage['username'] . "</strong> - " . $rowMessage['date_creation'] . "</p>";
                                    echo "<p>" . nl2br($rowMessage['contenu']) . "</p>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>Aucun message trouvé pour ce sujet.</p>";
                            }
                            ?>
                        </div>

                    </div>

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