<?php
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
if (!isset($_SESSION["username"])) {
    header("Location: ../pages_cnx/login.php");
    exit();
}
if (($_SESSION["idprofil"]) != 1) {
    header("Location: ../pages_cnx/login.php");
    exit();
}

$idmodif = ($_SESSION['id']);

if (isset($_POST['updateProfileImage'])) {
    $newProfileImageUrl = mysqli_real_escape_string($conn, $_POST['profileImageUrl']);

    $updateImageUrlQuery = "UPDATE users SET urlpfp = '$newProfileImageUrl' WHERE id = '$idmodif'";
    $updateImageUrlResult = $conn->query($updateImageUrlQuery);

    if ($updateImageUrlResult) {
        header("Location: ./profil.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour de l'URL de la photo de profil.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookWaves / Profil</title>
    <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/style-responsive.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cf0cc41982.js" crossorigin="anonymous"></script>
    <script>
        function redirectToPage(url) {
            window.location.href = url;
        }
    </script>
</head>

<body>
    <section id="container">
        <header class="header black-bg">
            <a href="../index.php" class="logo"><b><span>BOOK WAVES
                        <?php echo isset($_SESSION['username']) ? ' / ' . $_SESSION['username'] : ''; ?>
                    </span></b></a>
            <div class="top-menu">
                <ul class="nav pull-right top-menu">
                    <?php if (isset($_SESSION['username'])): ?>
                        <li><a class="logout" href="../pages_perso/livres_perso.php">Accueil</a></li>
                        <li><a class="logout"
                                href="../pages_cnx/logout.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Se
                                Déconnecter</a></li>
                    <?php else: ?>
                        <li><a class="logout" href="./pages_cnx/login.php">Se Connecter</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </header>

        <aside>
            <div id="sidebar" class="nav-collapse">
                <ul class="sidebar-menu" id="nav-accordion">
                    <li>
                        <a href="./profil.php" class="active">
                            <i class="fa fa-user"></i>
                            <span>Mes informations</span>
                        </a>
                    </li>
                    <li>
                        <a href="./modifusername.php" class="active">
                            <i class="fa fa-user"></i>
                            <span>Modifier mes informations</span>
                        </a>
                    </li>

                    <li>
                        <a href="./modifmdp.php" class="active">
                            <i class="fa fa-user"></i>
                            <span>Modifier mot de passe</span>
                        </a>
                    </li>
                    <li>
                        <a href="./modifpfp.php" class="active">
                            <i class="fa fa-user"></i>
                            <span>Modifier photo de profil</span>
                        </a>
                    </li>
                    <li>
                        <a href="./deleteacc.php" class="active">
                            <i class="fa fa-user"></i>
                            <span>Supprimer mon compte</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <section id="main-content">
            <section class="wrapper">
                <div class="parent-container">
                    <div class="content-container">
                        <div class="profile-actions">



                            <!-- Ajouter une section pour la modification d'utilisateur -->

                            <div id="containerx">
                                <form class="box" action="" method="post" name="modifyUserForm">
                                    <!-- Champ pour entrer le nouveau nom d'utilisateur -->
                                    <div class="box-utilisateur">
                                        <h2 style='color:white'>Nouveau nom d'utilisateur</h2>
                                        <input type="text" class="box-input" name="newUsername"
                                            placeholder="Nouveau nom d'utilisateur" required />
                                        <br>
                                        <h2 style='color:white'>Nouvel email</h2>
                                        <!-- Champ pour entrer le nouvel email -->
                                        <input type="text" class="box-input" name="newEmail" placeholder="Nouvel email"
                                            required />
                                        <br>
                                        <!-- Bouton de confirmation pour modifier l'utilisateur --> <br>
                                        <input type="submit" name="modifyUserSubmit" value="Mettre à jour"
                                            style='color:black' class="box-button" />
                                    </div>
                                </form>
                            </div>

                            <?php
                            $idmodif = ($_SESSION['id']);
                            if (isset($_POST['modifyUserSubmit'])) {
                                $userIdToModify = ($_SESSION['id']);
                                $newUsername = mysqli_real_escape_string($conn, $_POST['newUsername']);
                                $newEmail = mysqli_real_escape_string($conn, $_POST['newEmail']);

                                // Requête pour mettre à jour l'utilisateur sélectionné
                                $modifyUserQuery = "UPDATE users SET username='$newUsername', email='$newEmail' WHERE id = '$idmodif'";
                                $modifyUserResult = $conn->query($modifyUserQuery);

                                if ($modifyUserResult) {
                                    // Mettez à jour la liste des utilisateurs après la modification
                                    $userQuery = "SELECT id, username, email FROM users WHERE id = '$idmodif'";
                                    $userResult = $conn->query($userQuery);


                                    if ($userResult->num_rows > 0) {
                                        // Mettez à jour les variables de session avec les nouvelles informations
                                        $userData = $userResult->fetch_assoc();
                                        $_SESSION['username'] = $userData['username'];
                                        $_SESSION['email'] = $userData['email'];
                                        echo '<script>redirectToPage("./profil.php");</script>';
                                    }

                                }

                            }

                            ?>
                        </div>
                    </div>
                </div>
                </div>
                </div>
            </section>
        </section>
    </section>

    <!-- Ajoutez vos scripts JS ici -->

    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="lib/jquery.dcjqaccordion.2.7.js"></script>
    <script src="lib/jquery.scrollTo.min.js"></script>
    <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="lib/jquery.sparkline.js"></script>
    <script src="lib/common-scripts.js"></script>
    <script type="text/javascript" src="lib/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="lib/gritter-conf.js"></script>
    <script src="lib/sparkline-chart.js"></script>
    <script src="lib/zabuto_calendar.js"></script>
</body>

</html>