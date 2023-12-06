<?php
// Connexion à la base de données (utilisez vos propres informations)
$servername = "localhost";
$username = "root";
$password = "";
$database = "Biblio";

try {
    $connexion = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérez l'ID du livre depuis la requête GET
    $bookId = isset($_GET['id']) ? $_GET['id'] : 0;

    // Requête pour récupérer les informations du livre par son ID
    $requete = "SELECT livreperso.id AS id, livreperso.lienfiles AS lien, livreperso.lienfolder AS nomfichier, livreperso.nom AS nom, auteur.nom AS auteur, editeur.nom AS editeur, genre.nom AS genre, langue.nom AS langue
                FROM livreperso 
                JOIN auteur ON livreperso.idauteur = auteur.id 
                JOIN editeur ON livreperso.idediteur = editeur.id 
                JOIN genre ON livreperso.idgenre = genre.id 
                JOIN langue ON livreperso.idlangue = langue.id 
                WHERE livreperso.id = :bookId";

    $stmt = $connexion->prepare($requete);
    $stmt->bindValue(':bookId', $bookId, PDO::PARAM_INT);
    $stmt->execute();

    $bookInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Retournez les informations du livre au format JSON
    header('Content-Type: application/json');
    echo json_encode($bookInfo);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>