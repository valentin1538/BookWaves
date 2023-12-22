<?php
// SOUS PROJET HUGO DAVION

session_start();

// Vérifier si l'ID du message est défini dans le formulaire
if (isset($_POST['idmessage'])) {
    $idMessage = $_POST['idmessage'];
    
    // Connectez-vous à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "Biblio";
    $conn = new mysqli($servername, $username, $password, $database);

    // Vérifiez la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Récupérez l'ID de l'utilisateur connecté
    $userId = $_SESSION['id'];

    // Vérifiez si l'utilisateur a le droit de supprimer ce message (en tant qu'auteur du message)
    $result = $conn->query("SELECT idpersonne FROM message WHERE id = $idMessage");

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $messageAuthorId = $row['idpersonne'];

        // Vérifiez si l'utilisateur connecté est l'auteur du message
        if ($userId == $messageAuthorId) {
            // Supprimer le message
            $conn->query("DELETE FROM message WHERE id = $idMessage");
        }
    }

    // Fermez la connexion à la base de données
    $conn->close();
}

// Redirigez l'utilisateur vers la page des messages (peut être ajusté selon vos besoins)
header("Location: messages.php?idsujet=" . $_POST['idsujet']);
exit();
?>
