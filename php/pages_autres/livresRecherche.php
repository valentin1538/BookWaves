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
// Initialiser la session
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../pages_cnx/login.php");
    exit();
}
?>
<?php

// Requête pour récupérer les données des tables
$sql = "SELECT livre.id, livre.nom, livre.infos, auteur.nom as nom_auteur, editeur.nom as nom_editeur, genre.nom as nom_genre, langue.nom as nom_langue FROM livre
        INNER JOIN auteur ON livre.idauteur = auteur.id
        INNER JOIN editeur ON livre.idediteur = editeur.id
        INNER JOIN genre ON livre.idgenre = genre.id
        INNER JOIN langue ON livre.idlangue = langue.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque</title>
    <!-- Bootstrap core CSS -->
    <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!--external css-->
    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/style-responsive.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cf0cc41982.js" crossorigin="anonymous"></script>
    <style>
        /* Styles CSS pour la représentation visuelle des livres */

        form {
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            font-size: 18px;
            margin-right: 10px;
        }

        input[type="text"],
        input[type="submit"] {
            padding: 10px;
            font-size: 16px;
        }

        input[type="submit"] {
            cursor: pointer;
        }

        input[type="submit"]:hover {}

        .container-2 {
            width: 80%;
            margin: 0 auto;

            padding: 20px;

            border-radius: 5px;
            padding-left: 150px;
        }

        .books {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .book {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;

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

        }

        .book p {
            font-size: 14px;

            margin-top: 5px;
        }

        .book a {

            text-decoration: none;
        }

        .book a:hover {
            text-decoration: underline;
        }
    </style>
</head>


<body>
    <section id="container">
        <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
        <!--header start-->
        <header class="header black-bg text-center">
            <!--logo start-->
            <a href="../index.php" class="logo"><b><span>BOOK WAVES
                        <?php echo isset($_SESSION['username']) ? ' / ' . $_SESSION['username'] : ''; ?>
                    </span></b></a>
            <!--logo end-->
            <ul class="nav pull-right top-menu">
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- Utilisateur connecté -->
                    <li><a class="logout"
                            href="../pages_cnx/logout.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Se
                            Déconnecter</a></li>
                <?php else: ?>
                    <!-- Utilisateur non connecté -->
                    <li><a class="logout" href="../pages_cnx/login.php">Se Connecter</a></li>
                <?php endif; ?>
            </ul>
        </header>
        <!--header end-->
        <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
        <!--sidebar start-->
        <aside>
            <div id="sidebar" class="nav-collapse">
                <!-- sidebar menu start-->
                <ul class="sidebar-menu" id="nav-accordion">
                    <li class="Formats">
                        <a href="#" id="biblioCommuneLink" class="menu-link">
                            <i class="fa fa-book"></i>
                            <span>Bibliothèque Commune</span>
                        </a>
                        <ul id="menuDeroulantCommun" class="menu-deroulant-commune">
                            <li class="auteur">
                                <a href="../index.php">
                                    <i class="fa fa-book-open"></i>
                                    <span>Livres</span>
                                </a>
                            </li>
                            <li class="auteur">
                                <a href="../pages_commune/auteurs_commune.php">
                                    <i class="fa fa-user-tie"></i>
                                    <span>Auteurs</span>
                                </a>
                            </li>
                            <li class="editeur">
                                <a href="../pages_commune/editeurs_commune.php">
                                    <i class="fa fa-feather"></i>
                                    <span>Editeurs</span>
                                </a>
                            </li>
                            <li class="Genres">
                                <a href="../pages_commune/genres_commune.php">
                                    <i class="fa fa-tags"></i>
                                    <span>Genres</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a href="#" id="biblioPersoLink" class="menu-link">
                            <i class="fa fa-book"></i>
                            <span>Bibliothèque Perso</span>
                        </a>
                        <ul id="menuDeroulantPerso" class="menu-deroulant-perso">
                            <li class="auteur">
                                <a href="../pages_perso/livres_perso.php">
                                    <i class="fa fa-book-open"></i>
                                    <span>Livres</span>
                                </a>
                            </li>
                            <li class="auteur">
                                <a href="../pages_perso/auteurs_perso.php">
                                    <i class="fa fa-user-tie"></i>
                                    <span>Auteurs</span>
                                </a>
                            </li>
                            <li class="editeur">
                                <a href="../pages_perso/editeurs_perso.php">
                                    <i class="fa fa-feather"></i>
                                    <span>Editeurs</span>
                                </a>
                            </li>
                            <li class="Genres">
                                <a href="../pages_perso/genres_perso.php">
                                    <i class="fa fa-tags"></i>
                                    <span>Genres</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sub-menu">
                        <a href="../pages_autres/livresRecherche.php" class="active">
                            <i class="fa fa-globe"></i>
                            <span>Recherche d'Ebook</span>
                        </a>
                    </li>
                    <li class="sub-menu">
                        <a href="../pages_forum/forum.php">
                            <i class="fa fa-rectangle-list"></i>
                            <span>Forums</span>
                        </a>
                    </li>
                </ul>
                <!-- sidebar menu end-->
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var menuLinks = document.querySelectorAll(".menu-link");

                    menuLinks.forEach(function (link) {
                        link.addEventListener("click", function (event) {
                            event.preventDefault();
                            var menu = this.nextElementSibling;
                            menu.style.display = (menu.style.display === "none" || menu.style.display === "") ? "block" : "none";
                        });
                    });
                });
            </script>
        </aside>
        <!--sidebar end-->

        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="main-chart">
                        <!--CUSTOM CHART START -->
                        <div class="border-head">
                            <h3>Recherche de Livres</h3>

                            <script>
                                function showTodayBooks() {
                                    const today = new Date().toISOString().split('T')[0];
                                    const api_url = `https://www.googleapis.com/books/v1/volumes?q=intitle:${today}&langRestrict=fr&orderBy=newest&printType=books&filter=partial&projection=lite`;

                                    fetch(api_url)
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.items && data.items.length > 0) {
                                                let booksHTML = "<div class='books'>";
                                                data.items.forEach(item => {
                                                    const volumeInfo = item.volumeInfo;
                                                    const publishedDate = volumeInfo.publishedDate ? volumeInfo.publishedDate : '';
                                                    if (publishedDate.includes(today)) {
                                                        booksHTML += "<div class='book'>";
                                                        booksHTML += `<h2><a href='${volumeInfo.infoLink}' target='_blank'>${volumeInfo.title}</a></h2>`;
                                                        if (volumeInfo.authors && volumeInfo.authors.length > 0) {
                                                            booksHTML += "<p>Auteur(s): " + volumeInfo.authors.join(", ") + "</p>";
                                                        } else {
                                                            booksHTML += "<p>Auteur(s): Information non disponible</p>";
                                                        }
                                                        booksHTML += "<p>Date de publication: " + (publishedDate ? new Date(publishedDate).toLocaleDateString('fr-FR') : '') + "</p>";
                                                        booksHTML += "</div>";
                                                    }
                                                });
                                                booksHTML += "</div>";

                                                document.getElementById('todayBooks').innerHTML = booksHTML;
                                                document.getElementById('todayBooks').classList.remove('hidden');
                                            } else {
                                                document.getElementById('todayBooks').innerHTML = "Aucun livre publié aujourd'hui.";
                                                document.getElementById('todayBooks').classList.remove('hidden');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Erreur lors de la récupération des données:', error);
                                            document.getElementById('todayBooks').innerHTML = "Erreur lors de la récupération des données.";
                                            document.getElementById('todayBooks').classList.remove('hidden');
                                        });
                                }
                            </script>

                            <div class="container">

                                <div class="notification">

                                    <span class="bell" onclick="showTodayBooks()" style="cursor: pointer;">🔔</span>
                                    <div id="todayBooks" class="hidden">
                                        <!-- Les livres du jour seront affichés ici -->
                                    </div>
                                </div>
                            </div>

                            <form method="GET">
                                <label for="searchTerm">Rechercher :</label>
                                <input type="text" id="searchTerm" name="searchTerm">

                                <label for="criteria">Critère :</label>
                                <input type="radio" id="criteria_books" name="criteria" value="books">
                                <label for="criteria_books">Livres</label>

                                <input type="radio" id="criteria_author" name="criteria" value="author">
                                <label for="criteria_author">Auteur</label>

                                <input type="radio" id="criteria_publisher" name="criteria" value="publisher"
                                    onclick="showPublisherSearch()">
                                <label for="criteria_publisher">Éditeur</label>

                                <div id="publisherSearch" style="display: none;">
                                    <label for="searchPublisherTerm">Rechercher un éditeur :</label>
                                    <input type="text" id="searchPublisherTerm" name="searchPublisherTerm">
                                </div>

                                <input type="submit" value="Rechercher">
                            </form>

                            <?php

                            function searchByAuthor($searchTerm)
                            {
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

                                            // Vérifiez si la clé 'title' existe avant de l'utiliser
                                            if (isset($volumeInfo['title'])) {
                                                $rechercheResult .= "<div class='book'>";

                                                // Récupération de l'URL de la première de couverture
                                                $thumbnail = isset($volumeInfo['imageLinks']['thumbnail']) ? $volumeInfo['imageLinks']['thumbnail'] : '';

                                                // Affichage de l'image de couverture si disponible
                                                if (!empty($thumbnail)) {
                                                    $rechercheResult .= "<img src='" . $thumbnail . "' alt='Couverture du livre'>";
                                                }

                                                // Construire le lien vers la page Google Play Books pour chaque livre
                                                $playStoreLink = isset($item['id']) ? "https://play.google.com/store/books/details?id={$item['id']}&source=gbs_api" : '';
                                                if (!empty($playStoreLink)) {
                                                    $rechercheResult .= "<h2><a href='" . $playStoreLink . "' target='_blank'>" . $volumeInfo['title'] . "</a></h2>";
                                                } else {
                                                    $rechercheResult .= "<h2>" . $volumeInfo['title'] . "</h2>";
                                                }

                                                if (isset($volumeInfo['authors']) && is_array($volumeInfo['authors'])) {
                                                    $rechercheResult .= "<p>Auteur(s): " . implode(", ", $volumeInfo['authors']) . "</p>";
                                                } else {
                                                    $rechercheResult .= "<p>Auteur(s): Information non disponible</p>";
                                                }

                                                $rechercheResult .= "<p>Date de publication: " . (isset($volumeInfo['publishedDate']) ? date("d-m-Y", strtotime($volumeInfo['publishedDate'])) : '') . "</p>";
                                                // Ajouter d'autres détails si nécessaire
                                                $rechercheResult .= "</div>";
                                            }
                                        }
                                        $rechercheResult .= "</div>";

                                        return $rechercheResult;
                                    } else {
                                        return "Aucun livre trouvé pour la recherche.";
                                    }
                                }
                            }


                            function searchBooks($searchTerm)
                            {
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

                                            // Récupération de l'URL de la première de couverture
                                            $thumbnail = isset($volumeInfo['imageLinks']['thumbnail']) ? $volumeInfo['imageLinks']['thumbnail'] : '';

                                            // Affichage de l'image de couverture si disponible
                                            if (!empty($thumbnail)) {
                                                $rechercheResult .= "<img src='" . $thumbnail . "' alt='Couverture du livre'>";
                                            }

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


                            function searchByPublisher($searchTerm)
                            {
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

                                            // Récupération de l'URL de la première de couverture
                                            $thumbnail = isset($volumeInfo['imageLinks']['thumbnail']) ? $volumeInfo['imageLinks']['thumbnail'] : '';

                                            // Affichage de l'image de couverture si disponible
                                            if (!empty($thumbnail)) {
                                                $rechercheResult .= "<img src='" . $thumbnail . "' alt='Couverture du livre'>";
                                            }

                                            // Construire le lien vers la page Google Play Books pour chaque livre
                                            $playStoreLink = isset($item['id']) ? "https://play.google.com/store/books/details?id={$item['id']}&source=gbs_api" : '';
                                            if (!empty($playStoreLink)) {
                                                $rechercheResult .= "<h2><a href='" . $playStoreLink . "' target='_blank'>" . $volumeInfo['title'] . "</a></h2>";
                                            } else {
                                                $rechercheResult .= "<h2>" . $volumeInfo['title'] . "</h2>";
                                            }

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

                            <script>

                                function searchByCollectionAndPublisher($collectionTerm, $publisherTerm) {
                                    $date = date('Y'). "-01-01";
                                    $collectionTerm = urlencode($collectionTerm);
                                    $publisherTerm = urlencode($publisherTerm);
                                    $api_url = "https://www.googleapis.com/books/v1/volumes?q={$collectionTerm}+inauthor:{$publisherTerm}&langRestrict=fr&orderBy=newest&printType=books&filter=partial&projection=lite&publishedDate={$date}";

                                    $response = file_get_contents($api_url);

                                    if ($response === false) {
                                        return "Erreur lors de la récupération des données.";
                                    } else {
                                        $data = json_decode($response, true);

                                        if (isset($data['items'])) {
                                            $books = $data['items'];

                                            $rechercheResult = "<div class='books'>";
                                            foreach($books as $item) {
                                                $volumeInfo = $item['volumeInfo'];

                                                $rechercheResult.= "<div class='book'>";
                                                // Ajout du lien autour du titre du livre si 'infoLink' est disponible
                                                if (isset($volumeInfo['infoLink'])) {
                                                    $rechercheResult.= "<h2><a href='".$volumeInfo['infoLink']. "' target='_blank'>".$volumeInfo['title']. "</a></h2>";
                                                } else {
                                                    $rechercheResult.= "<h2>".$volumeInfo['title']. "</h2>";
                                                }

                                                if (isset($volumeInfo['authors']) && is_array($volumeInfo['authors'])) {
                                                    $rechercheResult.= "<p>Auteur(s): ".implode(", ", $volumeInfo['authors']). "</p>";
                                                } else {
                                                    $rechercheResult.= "<p>Auteur(s): Information non disponible</p>";
                                                }

                                                $rechercheResult.= "<p>Date de publication: ". (isset($volumeInfo['publishedDate']) ? date("d-m-Y", strtotime($volumeInfo['publishedDate'])) : ''). "</p>";
                                                $rechercheResult.= "</div>";
                                            }
                                            $rechercheResult.= "</div>";

                                            return $rechercheResult;
                                        } else {
                                            return "Aucun livre trouvé pour la recherche.";
                                        }
                                    }
                                }

                                function showPublisherSearch() {
                                    document.getElementById('publisherSearch').style.display = 'block';
                                }

                                // Fonction pour cacher la barre de recherche de l'éditeur au chargement de la page
                                window.onload = function () {
                                    document.getElementById('publisherSearch').style.display = 'none';
                                }

                                // Détecter le changement de sélection du radio bouton pour Éditeur
                                document.getElementById('criteria_publisher').addEventListener('change', function () {
                                    if (this.checked) {
                                        showPublisherSearch();
                                    }
                                });




                            </script>


                        </div>

                        <!-- js placed at the end of the document so the pages load faster -->
                        <script src="../lib/jquery/jquery.min.js"></script>

                        <script src="../lib/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>