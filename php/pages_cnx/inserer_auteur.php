<!-- SOUS PROJET HUGO DAVION -->

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_auteur = $_POST["nom_auteur"];

    // Connexion à la base de données
    $servername = "localhost"; // Remplacez par le nom de votre serveur de base de données
    $username = "root"; // Remplacez par votre nom d'utilisateur de base de données
    $password = ""; // Remplacez par votre mot de passe de base de données
    $database = "Biblio"; // Remplacez par le nom de votre base de données

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Requête d'insertion de l'auteur
    $sql = "INSERT INTO auteur (nom) VALUES ('$nom_auteur')";

    if ($conn->query($sql) === TRUE) {
        echo "Auteur ajouté avec succès.";
    } else {
        echo "Erreur lors de l'insertion de l'auteur : " . $conn->error;
    }

    $conn->close();
}
?>