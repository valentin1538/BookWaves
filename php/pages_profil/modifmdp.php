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



                            <!-- Ajouter une section pour la modification du mot de passe -->
                            <div id="containerx">
                                <form class="box" action="" method="post" name="updatePasswordForm">
                                    <h2 style='color:white'>Ancien mot de passe</h2>
                                    <!-- Champ pour entrer l'ancien mot de passe pour vérification -->
                                    <div class="box-utilisateur">
                                        <input type="password" class="box-input" name="oldPassword"
                                            placeholder="Ancien mot de passe" required />
                                        <br>
                                        <h2 style='color:white'>Nouveau mot de passe</h2>
                                        <!-- Champ pour entrer le nouveau mot de passe -->
                                        <input type="password" class="box-input" name="newPassword"
                                            placeholder="Nouveau mot de passe" required />
                                        <br><br>
                                        <!-- Bouton de confirmation pour modifier le mot de passe -->
                                        <input type="submit" name="updatePasswordSubmit" value="Mettre à jour"
                                            style='color:black' class="box-button" />
                                    </div>
                                    <?php
                                    // Traitement de la modification du mot de passe
                                    if (isset($_POST['updatePasswordSubmit'])) {
                                        // Vérifiez que les champs de mot de passe sont définis
                                        if (isset($_POST['oldPassword'], $_POST['newPassword'])) {
                                            $userIdToUpdate = ($_SESSION['id']);
                                            $oldPassword = hash('sha256', $_POST['oldPassword']); // Crypte l'ancien mot de passe
                                            $newPassword = hash('sha256', $_POST['newPassword']); // Crypte le nouveau mot de passe
                                    
                                            // Requête pour récupérer le mot de passe stocké dans la base de données
                                            $getUserPasswordQuery = "SELECT password FROM users WHERE id = '$userIdToUpdate'";
                                            $getUserPasswordResult = $conn->query($getUserPasswordQuery);

                                            if ($getUserPasswordResult->num_rows > 0) {
                                                $userData = $getUserPasswordResult->fetch_assoc();
                                                $storedPassword = $userData['password'];

                                                // Vérifiez que l'ancien mot de passe correspond
                                                if ($oldPassword === $storedPassword) {
                                                    // Requête pour mettre à jour le mot de passe
                                                    $updatePasswordQuery = "UPDATE users SET password='$newPassword' WHERE id = '$userIdToUpdate'";
                                                    $updatePasswordResult = $conn->query($updatePasswordQuery);

                                                    if ($updatePasswordResult) {

                                                        echo "Votre mot de passe a été mis à jour";
                                                    } else {
                                                        echo "Erreur lors de la mise à jour du mot de passe.";
                                                    }
                                                } else {
                                                    echo "L'ancien mot de passe ne correspond pas.";
                                                }
                                            } else {
                                                echo "Utilisateur non trouvé.";
                                            }
                                        } else {
                                            echo "Les champs de mot de passe ne sont pas définis.";
                                        }
                                    }
                                    ?>
                                </form>


                            </div>
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