<?php
// Assurez-vous que le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "Biblio";
    $conn = new mysqli($servername, $username, $password, $database);

    // Vérifiez la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Assurez-vous que le champ 'nouveauSujet' et 'idforum' sont présents dans le formulaire
    if (isset($_POST['nouveauSujet']) && isset($_POST['idforum'])) {
        // Récupérez le titre du nouveau sujet depuis le formulaire
        $nouveauSujet = $_POST['nouveauSujet'];

        // Récupérez l'ID de l'utilisateur à partir de la session (assurez-vous que l'utilisateur est connecté)
        session_start();
        $idUtilisateur = isset($_SESSION['id']) ? $_SESSION['id'] : null;

        // Récupérez l'ID du forum à partir du formulaire
        $idForumActuel = $_POST['idforum'];

        // Vérifiez si l'ID de l'utilisateur est valide
        if ($idUtilisateur !== null) {
            // Préparer la requête d'insertion
            $stmt = $conn->prepare("INSERT INTO sujet (nom, idpersonne, idforum) VALUES (?, ?, ?)");

            // Liaison des paramètres
            $stmt->bind_param("sii", $nouveauSujet, $idUtilisateur, $idForumActuel);

            // Exécution de la requête
            if ($stmt->execute()) {
                // Rediriger vers la page des sujets après l'ajout
                header("Location: sujets.php?idforum=$idForumActuel");
                exit();
            } else {
                echo "Erreur lors de l'ajout du sujet : " . $stmt->error;
            }

            // Fermer la requête
            $stmt->close();
        } else {
            echo "Erreur : Utilisateur non connecté.";
        }
    } else {
        echo "Erreur : Champs 'nouveauSujet' ou 'idforum' non présents dans le formulaire.";
    }
} else {
    // Si le formulaire n'a pas été soumis correctement, redirigez l'utilisateur vers la page d'origine
    header("Location: sujets.php");
    exit();
}
?>
