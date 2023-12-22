<?php
// SOUS PROJET VALENTIN PREVOT

session_start();
// Connexion à la base de données MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Biblio";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if (!isset($_SESSION['id'])) {
    http_response_code(401); // Non autorisé
    exit("Utilisateur non connecté");
}

$userId = $_SESSION['id'];
$bookId = isset($_POST['bookId']) ? $_POST['bookId'] : null;

if ($bookId === null) {
    http_response_code(400); // Requête incorrecte
    exit("Identifiant de livre manquant");
}


$sqlCheckBook = "SELECT nom, lienfiles FROM livre WHERE id = $bookId";
$resultCheckBook = $conn->query($sqlCheckBook);

if ($resultCheckBook->num_rows > 0) {
    $row = $resultCheckBook->fetch_assoc();
    $nomLivre = $row['nom'];
    $lienFilesLivre = $row['lienfiles'];

    // Vérifier si le livre existe déjà dans la bibliothèque personnelle de l'utilisateur
    $sqlCheckPersonalLibrary = "SELECT * FROM livreperso WHERE idpersonne = $userId AND nom = '$nomLivre' AND lienfiles = '$lienFilesLivre'";
    $resultCheckPersonalLibrary = $conn->query($sqlCheckPersonalLibrary);

    if ($resultCheckPersonalLibrary->num_rows > 0) {
        http_response_code(400);
        exit("Le livre est déjà dans votre bibliothèque personnelle.");
    }

    // Le livre existe dans la bibliothèque commune, ajoutez-le à la bibliothèque personnelle
    $sqlAddToPersonalLibrary = "INSERT INTO livreperso (idpersonne, nom, lienfiles, lienfolder, idauteur, idediteur, idgenre, idlangue, idcollection, source) 
                                SELECT $userId, nom, lienfiles, lienfolder, idauteur, idediteur, idgenre, idlangue, idcollection, 1 FROM livre WHERE id = $bookId";

    if ($conn->query($sqlAddToPersonalLibrary) === TRUE) {
        echo "Livre ajouté à la bibliothèque personnelle avec succès!";
    } else {
        http_response_code(500);
        echo "Erreur lors de l'ajout du livre à la bibliothèque personnelle : " . $conn->error;
    }
} else {
    http_response_code(404);
    echo "Le livre n'existe pas dans la bibliothèque commune.";
}

$conn->close();

?>