<?php
// SOUS PROJET GAUTHIER ET VALENTIN PERREIRA

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer l'ID de l'utilisateur connecté depuis la base de données
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $sessionId = $_SESSION['id'];

        $query = "SELECT id FROM auteur WHERE nom = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $creatorID = $row['id'];
            // Utilisez $creatorID pour associer l'utilisateur en tant qu'auteur du livre
        } else {
            // Ajouter l'utilisateur dans la table des auteurs s'il n'est pas trouvé
            $insertQuery = "INSERT INTO auteur (nom) VALUES (?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("s", $username);
            $insertStmt->execute();

            if ($insertStmt->affected_rows === 1) {
                // Récupérer l'ID de l'utilisateur nouvellement ajouté
                $creatorID = $insertStmt->insert_id;
                // Utilisez $creatorID pour associer l'utilisateur en tant qu'auteur du livre
            } else {
                // Gérer une éventuelle erreur lors de l'ajout de l'utilisateur dans la table des auteurs
            }
        }
    }

    // Récupération des données du formulaire
    $title = $_POST['title'];   
    $publisherID = $_POST['publisher'];
    $languageID = $_POST['language'];
    $subjectID = $_POST['subject'];
    $contenu = $_POST['contenu'];
    // Récupération de l'image de couverture
    if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] === UPLOAD_ERR_OK) {
        $coverImageTmp = $_FILES['coverImage']['tmp_name']; // Emplacement temporaire du fichier
        $coverImageName = 'cover.jpg'; // Nouveau nom pour l'image       
    }


    // Récupération des noms correspondants aux ID pour les métadonnées du livre
    $queryCreator = $conn->query("SELECT nom FROM auteur WHERE id = $creatorID");
    $queryPublisher = $conn->query("SELECT nom FROM editeur WHERE id = $publisherID");
    $queryLanguage = $conn->query("SELECT nom FROM langue WHERE id = $languageID");
    $querySubject = $conn->query("SELECT nom FROM genre WHERE id = $subjectID");

    // Récupération des noms correspondants
    $creatorName = $queryCreator->fetch_assoc()['nom'];
    $publisherName = $queryPublisher->fetch_assoc()['nom'];
    $languageName = $queryLanguage->fetch_assoc()['nom'];
    $subjectName = $querySubject->fetch_assoc()['nom'];

    // Chemins des répertoires source et destination
    $repertoireSource = '../lib/bookTemplate';
    $repertoireDestination = '../lib/Librairy/' . $title;

    // Fonction pour copier récursivement le contenu d'un répertoire
    function copyDirectory($source, $destination) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                $dir = $destination . '/' . $iterator->getSubPathName();
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
            } else {
                $file = $destination . '/' . $iterator->getSubPathName();
                copy($item, $file);
            }
        }
    }

    // Vérifier si le répertoire de destination n'existe pas avant de le créer
    if (!is_dir($repertoireDestination)) {
        // Appel de la fonction pour copier le répertoire source vers le répertoire de destination avec le nouveau nom
        copyDirectory($repertoireSource, $repertoireDestination);

        // Chemin complet du fichier content.opf
        $cheminContentOPF = $repertoireDestination . '/OPS/content.opf';

        // Si une image a été téléchargée, la déplacer dans le dossier OPS du nouveau livre
        if (!empty($coverImageTmp) && file_exists($coverImageTmp)) {
            $coverDestination = $repertoireDestination . '/OPS/' . $coverImageName;
            move_uploaded_file($coverImageTmp, $coverDestination);
        }

        // Lire le contenu du fichier content.opf pour le modifier ensuite
        $contentOPF = file_get_contents($cheminContentOPF);

        // Remplacer les métadonnées avec les noms récupérés
        $contentOPF = str_replace(
            ['<dc:title>Le Lorem</dc:title>', '<dc:creator opf:file-as="Lorem" opf:role="aut">Lolo</dc:creator>', '<dc:language>fr</dc:language>', '<dc:subject>Genre</dc:subject>', '<dc:publisher>Editeur</dc:publisher>'],
            ['<dc:title>' . $title . '</dc:title>', '<dc:creator opf:file-as="' . $creatorName . '" opf:role="aut">' . $creatorName . '</dc:creator>', '<dc:language>' . $languageName . '</dc:language>', '<dc:subject>' . $subjectName . '</dc:subject>', '<dc:publisher>' . $publisherName . '</dc:publisher>'],
            $contentOPF
        );

        // Écrire les modifications dans le fichier content.opf
        file_put_contents($cheminContentOPF, $contentOPF);


        // Chemin complet du fichier book_0000.xhtml et MODIFICATION
        $cheminBook0XHTML = $repertoireDestination . '/OPS/book_0000.xhtml';
        $contentBook0XHTML = file_get_contents($cheminBook0XHTML);
        $contentBook0XHTML = str_replace(
            ['<p class="Auteur">Auteur</p>', '<p class="Titre_livre">Titre</p>'],
            ['<p class="Auteur">' . $creatorName . '</p>', '<p class="Titre_livre">' . $title . '</p>'],
            $contentBook0XHTML
        );
        file_put_contents($cheminBook0XHTML, $contentBook0XHTML);

        // Chemin complet du fichier book_0001.xhtml et MODIFICATION
        $cheminBookXHTML = $repertoireDestination . '/OPS/book_0001.xhtml';
        $contentBookXHTML = file_get_contents($cheminBookXHTML);
        $contentBookXHTML = str_replace(
            '<p class="Standard">Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum sunt porro obcaecati dolor pariatur maxime cumque dignissimos, debitis vero aliquam reiciendis quidem eaque ducimus iste voluptas dicta perferendis officiis similique!</p>',
            '<p class="Standard">' . $contenu . '</p>',
            $contentBookXHTML
        );
        file_put_contents($cheminBookXHTML, $contentBookXHTML);

        // Appel de la fonction pour créer le fichier ePub après la modification des métadonnées
        createEPUB($title);

        // Appel de la fonction pour ajouter le livre dans la base de données
        ajoutLivreBDD($sessionId, $title, $creatorID, $languageID, $subjectID, $publisherID, $conn);

    } else {
        echo "Le répertoire avec le nom du livre existe déjà.";
    }
} else {
    echo "Erreur : Le formulaire n'a pas été soumis.";
}



//fonction pour gérer la création du fichier .epub une fois que le bookTemplate à été dupliqué et modifié avec les info saisies dans le formulaire de création d'un livre
function createEPUB($title) {
    $repertoireDestination = '../lib/Librairy/' . $title;
    $epubFilename = $repertoireDestination . '/' . $title . '.epub';

    $zip = new ZipArchive();

    if ($zip->open($epubFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        // Ajout du mimetype sans compression
        $zip->addFile($repertoireDestination . '/mimetype', 'mimetype');

        // Copie du contenu de META-INF dans le fichier EPUB compressé
        $metaInfDir = $repertoireDestination . '/META-INF';
        $metaInfFiles = scandir($metaInfDir);
        foreach ($metaInfFiles as $file) {
            if ($file !== '.' && $file !== '..') {
                $zip->addFile($metaInfDir . '/' . $file, 'META-INF/' . $file);
            }
        }

        // Copie du contenu de OPS dans le fichier EPUB compressé
        $opsDir = $repertoireDestination . '/OPS';
        $opsFiles = scandir($opsDir);
        foreach ($opsFiles as $file) {
            if ($file !== '.' && $file !== '..') {
                $zip->addFile($opsDir . '/' . $file, 'OPS/' . $file);
            }
        }

        $zip->close();
        return "Le livre a été créé avec succès !";
    } else {
        return "Une erreur est survenue lors de la création du livre.";
    }
}


//AJOUTE LE LIVRE DANS LA BASE SI LE LIVRE .epub a été crée avec succès
function ajoutLivreBDD($sessionId, $title, $creatorID, $languageID, $subjectID, $publisherID, $conn) {

    //lien vers le fichier content.opf
    $lienFiles = $title . '/OPS/content.opf';

    // Préparation de la requête SQL d'insertion
    $stmt = $conn->prepare("INSERT INTO livreperso (idpersonne, nom, lienfiles, lienfolder, idauteur, idediteur, idgenre, idlangue) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Liaison des paramètres avec la requête SQL
    $stmt->bind_param("isssiiii",$sessionId, $title, $lienFiles, $title, $creatorID, $publisherID, $subjectID, $languageID);

    // Exécution de la requête
    $stmt->execute();

    // Vérification de l'insertion du livre dans la base de données
    if ($stmt->affected_rows === 1) {
        echo "Le livre a été créé avec succès !";
    } else {
        echo "Une erreur est survenue lors de l'ajout du livre dans la base de données.";
    }
    
    // Fermeture du statement
    $stmt->close();
}

