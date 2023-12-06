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
                        <a href="../pages_profil/modifusername.php" class="active">
                            <i class="fa fa-user"></i>
                            <span>Modifier mes informations</span>
                        </a>
                    </li>

                    <li>
                        <a href="../pages_profil/modifmdp.php" class="active">
                            <i class="fa fa-user"></i>
                            <span>Modifier mot de passe</span>
                        </a>
                    </li>
                    <li>
                        <a href="../pages_profil/modifpfp.php" class="active">
                            <i class="fa fa-user"></i>
                            <span>Modifier photo de profil</span>
                        </a>
                    </li>
                    <li>
                        <a href="../pages_profil/deleteacc.php" class="active">
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
                    <div class="containerhhh">
                        <div class="content-container">
                            <div class="profile-actions">


                                <div class="profile-details">
                                    <h2 style="color:white">ID :
                                        <?php echo ($_SESSION['id']) ?>
                                    </h2>
                                    <h2 style="color:white">Nom d'utilisateur :
                                        <?php echo ($_SESSION['username']) ?>
                                    </h2>
                                    <h2 style="color:white">Email :
                                        <?php echo ($_SESSION['email']) ?>
                                    </h2>
                                </div>


                                <div class="profile-container">
                                    <?php
                                    $userId = $_SESSION['id'];
                                    $profileImageQuery = "SELECT urlpfp FROM users WHERE id = '$userId'";
                                    $profileImageResult = $conn->query($profileImageQuery);

                                    if ($profileImageResult->num_rows > 0) {
                                        $userData = $profileImageResult->fetch_assoc();
                                        $profileImageUrl = $userData['urlpfp'];

                                        if (!empty($profileImageUrl)) {
                                            echo "<img id='previewImage' class='profile-image' src='$profileImageUrl' alt='Photo de profil' onclick='toggleUploadForm()' />";
                                        } else {
                                            echo "<img id='previewImage' class='profile-image' src='../images/PP/defaut.jpg' alt='Photo de profil' onclick='toggleUploadForm()' />";
                                        }
                                    } else {
                                        echo "<img id='previewImage' class='profile-image' src='../images/PP/defaut.jpg' alt='Photo de profil' onclick='toggleUploadForm()' />";


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