<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$database = "Biblio";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer la valeur du nouvel langue depuis la requête
    $nouvelLangue = isset($_POST['nouvelLangue']) ? $_POST['nouvelLangue'] : '';

    // Vérifier si la valeur n'est pas vide
    if (!empty($nouvelLangue)) {
        // Préparer et exécuter la requête SQL pour insérer le nouvel langue dans la table
        $sql = "INSERT INTO langue (nom) VALUES (:nom)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom', $nouvelLangue);
        $stmt->execute();
        // Vous pouvez ajouter d'autres actions ici si nécessaire
    }
} catch (PDOException $e) {
    // Gérer les erreurs de connexion ou d'insertion dans la base de données
    echo "Erreur : " . $e->getMessage();
}
?>