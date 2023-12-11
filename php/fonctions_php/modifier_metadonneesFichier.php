<?php
// Informations de connexion à la base de données
$servername = "localhost"; // Remplacez par le nom de votre serveur de base de données
$username = "root"; // Remplacez par votre nom d'utilisateur de base de données
$password = ""; // Remplacez par votre mot de passe de base de données
$database = "Biblio"; // Remplacez par le nom de votre base de données

try {
    // Création de la connexion à la base de données
    $connexion = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Définition des attributs de PDO pour gérer les erreurs
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des auteurs
    $query_auteurs = "SELECT id, nom FROM auteur";
    $result_auteurs = $connexion->query($query_auteurs);
    $auteurs = $result_auteurs->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des éditeurs
    $query_editeurs = "SELECT id, nom FROM editeur";
    $result_editeurs = $connexion->query($query_editeurs);
    $editeurs = $result_editeurs->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des genres
    $query_genres = "SELECT id, nom FROM genre";
    $result_genres = $connexion->query($query_genres);
    $genres = $result_genres->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des langues
    $query_langues = "SELECT id, nom FROM langue";
    $result_langues = $connexion->query($query_langues);
    $langues = $result_langues->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // En cas d'erreur de connexion à la base de données
    echo "Erreur de connexion : " . $e->getMessage();
}
?>