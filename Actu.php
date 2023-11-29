<!DOCTYPE html>
<html>
<head>
    <title>Actualités d'ebooks</title>
    <style>
        /* Styles CSS pour la représentation visuelle des livres */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        h1 {
            text-align: center;
            margin-top: 30px;
            color: #333;
        }
        form {
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            font-size: 18px;
            margin-right: 10px;
        }
        input[type="text"], input[type="submit"] {
            padding: 10px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #555;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .books {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* Ajustement de la largeur minimale */
        gap: 10px; /* Réduction de l'espace entre les blocs */
    }
    

    .book {
        text-align: center;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .book img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
    }

    .book h2 {
        font-size: 16px;
        margin-top: 10px;
        color: #333;
    }

    .book p {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }

    .book a {
        color: #0066cc;
        text-decoration: none;
    }

    .book a:hover {
        text-decoration: underline;
    }
    </style>
</head>
<body>

<div class="container">
    <h1>Actualités d'ebooks</h1>

    <form method="GET">
        <label for="searchTerm">Rechercher :</label>
        <input type="text" id="searchTerm" name="searchTerm">

        <label for="criteria">Critère :</label>
        <input type="radio" id="criteria_books" name="criteria" value="books">
        <label for="criteria_books">Livres</label>

        <input type="radio" id="criteria_author" name="criteria" value="author">
        <label for="criteria_author">Auteur</label>

        <input type="radio" id="criteria_publisher" name="criteria" value="publisher">
        <label for="criteria_publisher">Éditeur</label>

        <input type="submit" value="Rechercher">
    </form>

    <?php
session_start();

function searchByAuthor($searchTerm) {
    $date = date('Y') . "-01-01";
    $searchTerm = urlencode($searchTerm);
    $api_url = "https://www.googleapis.com/books/v1/volumes?q=inauthor:{$searchTerm}&langRestrict=fr&orderBy=newest&printType=books&filter=partial&projection=lite&publishedDate={$date}";

    $response = file_get_contents($api_url);

    if ($response === false) {
        return "Erreur lors de la récupération des données.";
    } else {
        $data = json_decode($response, true);

        if (isset($data['items'])) {
            $books = $data['items'];

            $rechercheResult = "<div class='books'>";
            foreach ($books as $item) {
                $volumeInfo = $item['volumeInfo'];

                $rechercheResult .= "<div class='book'>";
                $rechercheResult .= "<h2>" . $volumeInfo['title'] . "</h2>";
                $rechercheResult .= "<p>Auteur(s): " . implode(", ", $volumeInfo['authors']) . "</p>";
                $rechercheResult .= "<p>Date de publication: " . (isset($volumeInfo['publishedDate']) ? date("d-m-Y", strtotime($volumeInfo['publishedDate'])) : '') . "</p>";
                // Ajoutez d'autres détails si nécessaire
                $rechercheResult .= "</div>";
            }
            $rechercheResult .= "</div>";

            return $rechercheResult;
        } else {
            return "Aucun livre trouvé pour la recherche.";
        }
    }
}

function searchBooks($searchTerm) {
    $encodedSearchTerm = rawurlencode($searchTerm);
    $api_url = "https://www.googleapis.com/books/v1/volumes?q=intitle:{$encodedSearchTerm}&langRestrict=fr&orderBy=newest&printType=books&filter=partial&projection=lite";

    $response = file_get_contents($api_url);

    if ($response === false) {
        return "Erreur lors de la récupération des données.";
    } else {
        $data = json_decode($response, true);

        if (isset($data['items'])) {
            $books = $data['items'];

            $rechercheResult = "<div class='books'>";
            foreach ($books as $item) {
                $volumeInfo = $item['volumeInfo'];
            
                $rechercheResult .= "<div class='book'>";
                // Ajoutez un lien autour du titre du livre
                $rechercheResult .= "<h2><a href='" . $volumeInfo['infoLink'] . "' target='_blank'>" . $volumeInfo['title'] . "</a></h2>";
            

                // Vérification de la clé 'authors' avant de l'utiliser
                if (isset($volumeInfo['authors']) && is_array($volumeInfo['authors'])) {
                    $rechercheResult .= "<p>Auteur(s): " . implode(", ", $volumeInfo['authors']) . "</p>";
                } else {
                    $rechercheResult .= "<p>Auteur(s): Information non disponible</p>";
                }

                $rechercheResult .= "<p>Date de publication: " . (isset($volumeInfo['publishedDate']) ? date("d-m-Y", strtotime($volumeInfo['publishedDate'])) : '') . "</p>";
                // Ajoutez d'autres détails du livre si nécessaire
                $rechercheResult .= "</div>";
            }
            $rechercheResult .= "</div>";

            return $rechercheResult;
        } else {
            return "Aucun livre trouvé pour la recherche.";
        }
    }
}


function searchByPublisher($searchTerm) {
    $date = date('Y') . "-01-01";
    $searchTerm = urlencode($searchTerm);
    $api_url = "https://www.googleapis.com/books/v1/volumes?q=inpublisher:{$searchTerm}&langRestrict=fr&orderBy=newest&printType=books&filter=partial&projection=lite&publishedDate={$date}";

    
    $response = file_get_contents($api_url);

    if ($response === false) {
        return "Erreur lors de la récupération des données.";
    } else {
        $data = json_decode($response, true);

        if (isset($data['items'])) {
            $books = $data['items'];

            $rechercheResult = "<div class='books'>";
            foreach ($books as $item) {
                $volumeInfo = $item['volumeInfo'];

                $rechercheResult .= "<div class='book'>";
                $rechercheResult .= "<h2>" . $volumeInfo['title'] . "</h2>";

                // Vérification de la clé 'editors' avant de l'utiliser
                if (isset($volumeInfo['editors']) && is_array($volumeInfo['editors'])) {
                    $rechercheResult .= "<p>Editeur(s): " . implode(", ", $volumeInfo['editors']) . "</p>";
                } else {
                    $rechercheResult .= "<p>Editeur(s): " . urldecode($searchTerm) . "</p>";
                }

                $rechercheResult .= "<p>Date de publication: " . (isset($volumeInfo['publishedDate']) ? date("d-m-Y", strtotime($volumeInfo['publishedDate'])) : '') . "</p>";
                // Ajoutez d'autres détails si nécessaire
                $rechercheResult .= "</div>";
            }
            $rechercheResult .= "</div>";

            return $rechercheResult;
        } else {
            return "Aucun livre trouvé pour la recherche.";
        }
    }
}

if (isset($_GET['searchTerm']) && !empty($_GET['searchTerm']) && isset($_GET['criteria']) && !empty($_GET['criteria'])) {
    $criteria = $_GET['criteria'];
    $searchTerm = $_GET['searchTerm'];
    $rechercheResult = '';

    switch ($criteria) {
        case "author":
            $rechercheResult = searchByAuthor($searchTerm);
            break;
        case "books":
            $rechercheResult = searchBooks($searchTerm);
            break;
        case "publisher":
            $rechercheResult = searchByPublisher($searchTerm);
            break;
        // Ajoutez d'autres cas pour les critères supplémentaires si nécessaire.
        default:
            $rechercheResult = "Critère de recherche invalide.";
    }

    echo $rechercheResult;
}

?>

</div>
</body>
</html>