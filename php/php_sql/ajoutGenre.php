<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$database = "Biblio";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer la valeur du nouveau genre depuis la requête
    $nouvelGenre = isset($_POST['nouvelGenre']) ? $_POST['nouvelGenre'] : '';

    // Vérifier si la valeur n'est pas vide
    if (!empty($nouvelGenre)) {
        // Préparer et exécuter la requête SQL pour insérer le nouveau genre dans la table
        $sql = "INSERT INTO genre (nom) VALUES (:nom)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom', $nouvelGenre);
        $stmt->execute();
        // Vous pouvez ajouter d'autres actions ici si nécessaire
    }
} catch (PDOException $e) {
    // Gérer les erreurs de connexion ou d'insertion dans la base de données
    echo "Erreur : " . $e->getMessage();
}
?>