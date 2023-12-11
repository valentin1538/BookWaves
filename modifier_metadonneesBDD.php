<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$database = "Biblio";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);

// Récupérer les valeurs variables à partir du formulaire
$idLivre = $data['id'];
$titre = $data['titre'];
$idAuteur = $data['auteur'];
$idEditeur = $data['editeur'];
$idLangue = $data['langue'];
$idGenre = $data['genre'];

// Mettre à jour le titre du livre dans la table 'livre'
$sqlTitre = "UPDATE livre SET nom = '$titre' WHERE id = '$idLivre'";
if ($conn->query($sqlTitre) === TRUE) {
    echo "Titre du livre mis à jour avec succès.";
} else {
    echo "Erreur lors de la mise à jour du titre du livre : " . $conn->error;
}

// Mettre à jour l'auteur du livre dans la table 'livre'
$sqlAuteur = "UPDATE livre SET idauteur = '$idAuteur' WHERE id = '$idLivre'";
if ($conn->query($sqlAuteur) === TRUE) {
    echo "Auteur du livre mis à jour avec succès.";
} else {
    echo "Erreur lors de la mise à jour de l'auteur du livre : " . $conn->error;
}

// Mettre à jour l'éditeur du livre dans la table 'livre'
$sqlEditeur = "UPDATE livre SET idediteur = '$idEditeur' WHERE id = '$idLivre'";
if ($conn->query($sqlEditeur) === TRUE) {
    echo "Éditeur du livre mis à jour avec succès.";
} else {
    echo "Erreur lors de la mise à jour de l'éditeur du livre : " . $conn->error;
}

// Mettre à jour la langue du livre dans la table 'livre'
$sqlLangue = "UPDATE livre SET idlangue = '$idLangue' WHERE id = '$idLivre'";
if ($conn->query($sqlLangue) === TRUE) {
    echo "Langue du livre mise à jour avec succès.";
} else {
    echo "Erreur lors de la mise à jour de la langue du livre : " . $conn->error;
}

// Mettre à jour le genre du livre dans la table 'livre'
$sqlGenre = "UPDATE livre SET idgenre = '$idGenre' WHERE id = '$idLivre'";
if ($conn->query($sqlGenre) === TRUE) {
    echo "Genre du livre mis à jour avec succès.";
} else {
    echo "Erreur lors de la mise à jour du genre du livre : " . $conn->error;
}

$conn->close(); // Fermer la connexion à la base de données
?>
