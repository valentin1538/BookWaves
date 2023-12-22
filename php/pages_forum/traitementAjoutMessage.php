<?php
// SOUS PROJET HUGO DAVION

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

    // Récupérez les données du formulaire
    $idsujet = $_POST['idsujet'];
    $contenu = $_POST['contenu'];

    // Récupérez l'ID de l'utilisateur à partir de la session (vous pouvez ajuster cela en fonction de votre système d'authentification)
    session_start();
    $idpersonne = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

    // Préparez la requête d'insertion avec la date actuelle
    $stmt = $conn->prepare("INSERT INTO message (contenu, idsujet, idpersonne, date_creation) VALUES (?, ?, ?, NOW())");

    // Liez les paramètres
    $stmt->bind_param("sii", $contenu, $idsujet, $idpersonne);

    // Exécutez la requête
    if ($stmt->execute()) {
        // Redirigez l'utilisateur vers la page des messages après l'ajout
        header("Location: messages.php?idsujet=$idsujet");
        exit();
    } else {
        // En cas d'erreur, affichez un message d'erreur
        echo "Erreur lors de l'ajout du message : " . $stmt->error;
    }

    // Fermez la déclaration et la connexion
    $stmt->close();
    $conn->close();
} else {
    // Si le formulaire n'a pas été soumis correctement, redirigez l'utilisateur vers la page d'origine
    header("Location: messages.php");
    exit();
}
?>
