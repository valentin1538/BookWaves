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

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    // Redirigez l'utilisateur vers la page de connexion si non connecté
    header("Location: ../pages_cnx/login.php");
    exit();
}

// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nomSujet = $_POST["nom_sujet"];

// Récupérer l'ID du forum depuis la session
$idforum = isset($_SESSION['idforum']) ? $_SESSION['idforum'] : null;

// Vérifier si l'ID du forum est valide
if ($idforum === null || !is_numeric($idforum)) {
    die("ID de forum non valide.");
}


    // Récupérer l'ID de la personne à partir de la session
    $idPersonne = $_SESSION['id'];

    // Vérifier si les données du formulaire sont valides
    if (empty($nomSujet) || empty($idForum) || empty($idPersonne)) {
        $erreur = "Tous les champs sont obligatoires.";
    } else {
        // Insérer le nouveau sujet dans la base de données
        $insertionSujet = $conn->query("INSERT INTO sujet (nom, idforum, idpersonne) VALUES ('$nomSujet', $idforum, $idPersonne)");

        // Vérifier si l'insertion a réussi
        if ($insertionSujet === false) {
            $erreur = "Erreur lors de l'ajout du sujet : " . $conn->error;
        } else {
            $succes = "Sujet ajouté avec succès.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque - Ajout de Sujet</title>
    <!-- Bootstrap core CSS -->
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- External CSS -->
    <link href="../css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cf0cc41982.js" crossorigin="anonymous"></script>
</head>
<body>
    <section id="container">
        <!-- Header -->
        <!-- ... (comme dans vos pages existantes) ... -->

        <!-- Main Content -->
        <section id="main-content">
            <section class="wrapper">
                <div class="forum">
                    <!-- Formulaire d'ajout de sujet -->
                    <div class="nouveau-sujet-form">
                        <h2>Créer un Nouveau Sujet</h2>
                        <form method="post" action="ajout_sujet.php?idforum=<?php echo $idforum; ?>">
                            <label for="nom_sujet">Nom du sujet :</label>
                            <input type="text" name="nom_sujet" required>
                            <button type="submit">Ajouter le sujet</button>
                        </form>
                        <?php
                        // Afficher les messages d'erreur ou de succès
                        if (isset($erreur)) {
                            echo "<p class='erreur'>$erreur</p>";
                        } elseif (isset($succes)) {
                            echo "<p class='succes'>$succes</p>";
                        }
                        ?>
                    </div>
                </div>
            </section>
        </section>

        <!-- Footer -->
        <!-- ... (comme dans vos pages existantes) ... -->
    </section>

    <!-- JS -->
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
