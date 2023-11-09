<?php
// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "Biblio");

if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}

// Fonction pour actualiser la liste des éditeurs
function actualiserListeEditeurs() {
    global $mysqli;
    $query = "SELECT id, nom FROM editeur";
    $result = $mysqli->query($query);
    $editeurs = array();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $editeurs[] = $row;
        }
        $result->free();
    }
    return $editeurs;
}

// Vérifiez si le formulaire a été soumis pour actualiser la liste des éditeurs
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualiserEditeurs"])) {
    $editeurs = actualiserListeEditeurs();
    echo json_encode($editeurs);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<script>
    function actualiserEditeurs() {
        // Utilisation de JavaScript pour envoyer une requête AJAX
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                var select = document.getElementById("editeurSelect");

                // Effacer les anciennes options
                select.innerHTML = "";

                // Ajouter les nouvelles options
                var defaultOption = document.createElement("option");
                defaultOption.text = "Sélectionnez un éditeur";
                select.add(defaultOption);

                response.forEach(function(editeur) {
                    var option = document.createElement("option");
                    option.value = editeur.id;
                    option.text = editeur.nom;
                    select.add(option);
                });
            }
        };

        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("actualiserEditeurs=1");
    }
</script>
    <meta charset="UTF-8">
    <title>Bibliothèque</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
</head>
<body>
    <header>
        <h1>Bibliothèques</h1>
    </header>
    <nav>
        <ul>
            <li><a href="#">Enregistrer</a></li>
            <li><a href="#">Ajouter Livre</a></li>
            <li><a href="#">Modifier Livre</a></li>
            <li><a href="#">Supprimer Livre</a></li>
        </ul>
    </nav>
    <main>
        <h2>Sélectionnez un éditeur :</h2>
        <form method="post">
            <select name="editeur" id="editeurSelect">
                <option value="">Sélectionnez un éditeur</option>
                <?php
                // Connexion à la base de données avec MySQLi
                $mysqli = new mysqli("localhost", "root", "", "Biblio");

                if ($mysqli->connect_error) {
                    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
                }

                // Requête SQL pour récupérer les éditeurs
                $query = "SELECT id, nom FROM editeur";
                $result = $mysqli->query($query);

                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $editeurId = $row['id'];
                        $selected = ($_POST['editeur'] == $editeurId) ? 'selected' : '';
                        echo "<option value='$editeurId' $selected>" . $row['nom'] . "</option>";
                    }
                } else {
                    echo "Erreur lors de la récupération des éditeurs : " . $mysqli->error;
                }

                $result->free(); // Libérer le résultat de la requête
                $mysqli->close(); // Fermer la connexion
                ?>
            </select>
            <input type="submit" value="Afficher les livres de l'éditeur">
            <input type="button" value="Actualiser Éditeurs" onclick="actualiserEditeurs()">
        </form>

        <h2>Livres publiés par l'éditeur sélectionné :</h2>
        <ul id="livresList">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editeur"]) && $_POST["editeur"] !== "") {
                $selectedEditeur = $_POST["editeur"];
                
                // Connexion à la base de données avec MySQLi
                $mysqli = new mysqli("localhost", "root", "", "Biblio");

                if ($mysqli->connect_error) {
                    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
                }

                // Requête SQL pour récupérer les livres de l'éditeur sélectionné
                $query = "SELECT nom FROM livre WHERE idediteur = $selectedEditeur";
                $result = $mysqli->query($query);

                if ($result) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<li>" . $row['nom'] . "</li>";
                        }
                    } else {
                        echo "<li>Aucun livre trouvé pour l'éditeur sélectionné.</li>";
                    }
                } else {
                    echo "Erreur lors de la récupération des livres : " . $mysqli->error;
                }

                $result->free(); // Libérer le résultat de la requête
                $mysqli->close(); // Fermer la connexion
            }
            ?>
        </ul>

        <!-- Formulaire pour attribuer un livre à un éditeur -->
        <form method="post">
            <h2>Attribuer un livre à un éditeur :</h2>
            <select name="livre">
                <option value="">Sélectionnez un livre</option>
                <?php
                // Connexion à la base de données avec MySQLi
                $mysqli = new mysqli("localhost", "root", "", "Biblio");

                if ($mysqli->connect_error) {
                    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
                }

                $livres = array(); // Créez un tableau pour stocker la liste des livres

                $queryLivres = "SELECT id, nom FROM livre";
                $resultLivres = $mysqli->query($queryLivres);

                if ($resultLivres) {
                    while ($row = $resultLivres->fetch_assoc()) {
                        $livres[] = $row; // Ajoutez chaque livre au tableau
                    }
                } else {
                    echo "Erreur lors de la récupération des livres : " . $mysqli->error;
                }

                $resultLivres->free(); // Libérer le résultat de la requête

                $editeurs = array(); // Créez un tableau pour stocker la liste des éditeurs

                $queryEditeurs = "SELECT id, nom FROM editeur";
                $resultEditeurs = $mysqli->query($queryEditeurs);

                if ($resultEditeurs) {
                    while ($row = $resultEditeurs->fetch_assoc()) {
                        $editeurs[] = $row; // Ajoutez chaque éditeur au tableau
                    }
                } else {
                    echo "Erreur lors de la récupération des éditeurs : " . $mysqli->error;
                }

                $resultEditeurs->free(); // Libérer le résultat de la requête

                $mysqli->close(); // Fermer la connexion

                foreach ($livres as $livre) {
                    echo "<option value='{$livre['id']}'>{$livre['nom']}</option>";
                }
                ?>
            </select>
            <select name="editeur">
                <option value="">Sélectionnez un éditeur</option>
                <?php
                foreach ($editeurs as $editeur) {
                    echo "<option value='{$editeur['id']}'>{$editeur['nom']}</option>";
                }
                ?>
            </select>
            <input type="submit" name="assignerLivre" value="Assigner le livre à l'éditeur">
        </form>
        <!-- Formulaire pour ajouter un nouvel éditeur à la liste déroulante -->
        <form method="post">
            <h2>Ajouter un nouvel éditeur :</h2>
            <input type="text" name="nouvelEditeur" placeholder="Nom du nouvel éditeur">
            <input type="submit" name="ajouterNouvelEditeur" value="Ajouter le nouvel éditeur">
        </form>

        <?php
// Gérer l'ajout d'un nouvel éditeur à la liste déroulante
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouterNouvelEditeur"])) {
            $nouvelEditeur = $_POST["nouvelEditeur"];

    // Connexion à la base de données avec MySQLi
            $mysqli = new mysqli("localhost", "root", "", "Biblio");

            if ($mysqli->connect_error) {
                die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
            }

    // Préparez la requête SQL pour insérer le nouvel éditeur
            $query = "INSERT INTO editeur (nom) VALUES (?)";
            $stmt = $mysqli->prepare($query);

    // Liez les paramètres
            $stmt->bind_param("s", $nouvelEditeur);

    // Exécutez la requête
            if ($stmt->execute()) {
                echo "Nouvel éditeur ajouté avec succès.";

            } 
            else 
            {
                echo "Erreur lors de l'ajout de l'éditeur : " . $mysqli->error;
            }

    $stmt->close(); // Fermer la requête préparée
    $mysqli->close(); // Fermer la connexion
}
?>

</ul>
</main>
</body>
</html>