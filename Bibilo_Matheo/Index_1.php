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
        <select id="editeurSelect">
            <option value="">Sélectionnez un éditeur</option>
            <option value="Adobe">Adobe</option>
            <option value="Microsoft">Microsoft</option>
            <option value="Eclipse Foundation">Eclipse Foundation</option>
            <option value="Test Erreur">Test Erreur</option>
        </select>

        <h2>Livres publiés par l'éditeur sélectionné :</h2>
        <ul id="livresList"></ul>
    </main>

    <script>
        // Tableau associatif des éditeurs et de leurs livres
        const editeursLivres = {
            "Adobe": ["Livre 1", "Livre 2", "Livre 3"],
            "Microsoft": ["Livre A", "Livre B", "Livre C"],
            "Eclipse Foundation": ["Livre X", "Livre Y", "Livre Z"]
        };

        // Événement de changement de sélection de l'éditeur
        const editeurSelect = document.getElementById("editeurSelect");
        const livresList = document.getElementById("livresList");

        editeurSelect.addEventListener("change", () => {
            const selectedEditeur = editeurSelect.value;
            livresList.innerHTML = ""; // Efface la liste précédente

            if (selectedEditeur !== "") {
                if (editeursLivres[selectedEditeur]) {
                    editeursLivres[selectedEditeur].forEach((livre) => {
                        const listItem = document.createElement("li");
                        listItem.textContent = livre;
                        livresList.appendChild(listItem);
                    });
                } else {
                    const listItem = document.createElement("li");
                    listItem.textContent = "Aucun livre trouvé pour l'éditeur sélectionné.";
                    livresList.appendChild(listItem);
                }
            }
        });
    </script>
</body>
</html>
