<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bibliothèque</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h1>Bibliothèque</h1>
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
    </main>
</body>
</html>
