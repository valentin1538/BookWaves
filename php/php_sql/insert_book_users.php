<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données envoyées par le formulaire
    $nom = $_POST['nom'] ?? '';
    $filesPath = $_POST['lienfiles'] ?? '';
    $folderPath = $_POST['lienfolder'] ?? '';
    $sessionId = $_POST['sessionId'] ?? '';
    $auteur = $_POST['auteur'] ?? '';
    $langue = $_POST['langue'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $editeur = $_POST['editeur'] ?? '';



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



    // Fonction pour obtenir l'ID ou ajouter une entrée dans une table
    function get_id_or_insert($conn, $table, $column, $value, $extra_fields = [])
    {
        $check_query = "SELECT id FROM $table WHERE $column = '$value'";
        $result = $conn->query($check_query);

        if ($result->num_rows > 0) {
            // L'entrée existe déjà, récupérer son ID
            $row = $result->fetch_assoc();
            return $row['id'];
        } else { // L'entrée n'existe pas, l'ajouter à la base de données avec des valeurs par défaut ou NULL
            $insert_query = "INSERT INTO $table ($column";

            // Ajouter des champs supplémentaires si nécessaire
            if (!empty($extra_fields)) {
                $insert_query .= ", " . implode(", ", array_keys($extra_fields));
            }

            $insert_query .= ") VALUES ('$value'";

            // Ajouter des valeurs par défaut ou NULL pour les champs supplémentaires
            foreach ($extra_fields as $field => $default_value) {
                $insert_query .= ", " . ($default_value !== null ? "'$default_value'" : "NULL");
            }

            $insert_query .= ")";

            if ($conn->query($insert_query) === TRUE) {
                return $conn->insert_id; // Renvoyer l'ID de la nouvelle entrée ajoutée
            } else {
                echo "Erreur lors de l'ajout de $value dans la table $table : " . $conn->error;
                $conn->close();
                exit(); // Arrêter le script en cas d'erreur
            }
        }
    }

    // Récupérer les IDs des autres éléments à partir de leur nom
    $id_auteur = get_id_or_insert($conn, 'auteur', 'nom', $auteur);
    $id_langue = get_id_or_insert($conn, 'langue', 'nom', $langue);
    $id_genre = get_id_or_insert($conn, 'genre', 'nom', $genre);
    $id_editeur = get_id_or_insert($conn, 'editeur', 'nom', $editeur);



    // Insérer le livre en utilisant les IDs récupérés
    $insert_livre_query = "INSERT INTO livreperso (idpersonne ,nom ,lienfiles, lienfolder, idauteur, idediteur, idgenre, idlangue) 
                           VALUES ('$sessionId','$nom', '$filesPath', '$folderPath',$id_auteur, $id_editeur, $id_genre, $id_langue)";

    if ($conn->query($insert_livre_query) === TRUE) {
        echo "Nouvel enregistrement créé avec succès.";
    } else {
        echo "Erreur lors de l'ajout du livre : " . $conn->error;
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
} else {
    echo "Erreur : méthode HTTP incorrecte.";
}
?>