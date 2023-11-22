<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Se connecter</title>
    <link rel="shortcut icon" href="">
    <link rel="stylesheet" href="../css/cnx.css">
</head>
<body>
    <center>
        <?php
        require('Config.php');
        session_start();

        if (isset($_POST['username'])) {
            $username = stripslashes($_REQUEST['username']);
            $username = mysqli_real_escape_string($conn, $username);

            $password = stripslashes($_REQUEST['password']);
            $password = mysqli_real_escape_string($conn, $password);

       // Hasher le mot de passe avec SHA-256 pour la comparaison
       $hashed_password = hash('sha256', $password);

            $query = "SELECT * FROM `users` WHERE username='$username' AND password='$hashed_password'";
            $result = $conn->query($query);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();

                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['username'] = $username;
                $_SESSION['idprofil'] = $row['idprofil'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['email'] = $row['email'];

                // Redirection en fonction du profil
                if ($row["idprofil"] == 4) {
                    header("Location: ../admin_ges/index_admin.php");
                    exit();
                } elseif ($row["idprofil"] == 1) {
                    header("Location: ../pages_perso/livres_perso.php");
                    exit();
                } elseif ($row["idprofil"] == 2) {
                    header("Location: ../pages_index/index_mod.php");
                    exit();
                } elseif ($row["idprofil"] == 3) {
                    header("Location: ../pages_index/index_dba.php");
                    exit();
                } else {
                    $message_erreur = "Profil inconnu";
                }
            } else {
                $message_erreur = "Nom d'utilisateur ou mot de passe incorrect";
            }
        }
        ?>
    </center>
    <br><br><br>
    <div id="container">
        <form class="box" action="" method="post" name="login">
            <h1>Connexion</h1>
            <h2>Nom d'utilisateur</h2>
            <input type="text" class="box-input" name="username" placeholder="Nom d'utilisateur">
            <br>
            <h2>Mot de passe</h2>
            <input type="password" class="box-input" name="password" placeholder="Mot de passe">
            <input type="submit" value="Confirmer " name="submit" style='color:Black' class="box-button">
            <h3 class="box-register">Vous pouvez aussi vous <a href="register.php" style='color:skyblue'> inscrire</a></h3>
            <h3 class="box-register">Ou revenir à <a href="../index.php" style='color:skyblue'> l'accueil</a></h3>

            <?php if (isset($message_erreur)) { ?>
                <p class="errorMessage"><?php echo $message_erreur; ?></p>
            <?php } ?>
        </form>
    </div>
</body>
</html>
