<?php
// Connexion à la base de données
$servername = "localhost"; // Remplacez par le nom de votre serveur de base de données
$username = "root"; // Remplacez par votre nom d'utilisateur de base de données
$password = ""; // Remplacez par votre mot de passe de base de données
$database = "Biblio"; // Remplacez par le nom de votre base de données

// Créez la connexion à la base de données
$conn = new mysqli($servername, $username, $password, $database);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Requête pour récupérer les données des tables
$sql = "SELECT livre.id, livre.nom, livre.infos, auteur.nom as nom_auteur, editeur.nom as nom_editeur, genre.nom as nom_genre, langue.nom as nom_langue FROM livre
        INNER JOIN auteur ON livre.idauteur = auteur.id
        INNER JOIN editeur ON livre.idediteur = editeur.id
        INNER JOIN genre ON livre.idgenre = genre.id
        INNER JOIN langue ON livre.idlangue = langue.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste de Livres</title>
             
                  <li><a href="index.php">Connexion</a></li>
                <li><a href="gestion_auteurs.php">Gestion des Auteurs</a></li>
</head>
<body>
    <h1>Liste de Livres</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Infos</th>
            <th>Auteur</th>
            <th>Éditeur</th>
            <th>Genre</th>
            <th>Langue</th>
        </tr>
        <?php
        if ($result ->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["nom"] . "</td>";
                echo "<td>" . $row["infos"] . "</td>";
                echo "<td>" . $row["nom_auteur"] . "</td>";
                echo "<td>" . $row["nom_editeur"] . "</td>";
                echo "<td>" . $row["nom_genre"] . "</td>";
                echo "<td>" . $row["nom_langue"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "Aucun livre trouvé dans la base de données.";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>