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
// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion

// Requête pour récupérer les données des tables
$sql = "SELECT livre.id, livre.nom, livre.infos, auteur.nom as nom_auteur, editeur.nom as nom_editeur, genre.nom as nom_genre, langue.nom as nom_langue FROM livre
        INNER JOIN auteur ON livre.idauteur = auteur.id
        INNER JOIN editeur ON livre.idediteur = editeur.id
        INNER JOIN genre ON livre.idgenre = genre.id
        INNER JOIN langue ON livre.idlangue = langue.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bibliothèque</title>
  <!-- Bootstrap core CSS -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/cf0cc41982.js" crossorigin="anonymous"></script>

</head>


<body>
  <section id="container">
    <!-- **********************************************************************************************************************************************************
          TOP BAR CONTENT & NOTIFICATIONS
          *********************************************************************************************************************************************************** -->
    <!--header start-->
    <header class="header black-bg">
      <!--logo start-->
      <a href="index.php" class="logo"><b><span>BOOK WAVES
            <?php echo isset($_SESSION['username']) ? ' / ' . $_SESSION['username'] : ''; ?>
          </span></b></a>
      <!--logo end-->
      <div class="nav notify-row text-center" id="top_menu">
        <!--  Categories start -->
        <ul class="nav top-menu">
          <!-- Ajout Livre Boutton start -->
          <li id="header_ajout_livre_bar" class="dropdown bars">

            <a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">
              Ajouter
              <i class="fa-solid fa-book-medical"></i>
            </a>
            <ul class="dropdown-menu extended notification">
              <div class="notify-arrow notify-arrow-green"></div>
              <li>
                <button id="add-book"><span class="label label-success"><i class="fa fa-download"></i></span>
                  Telecharger un fichier EPUB</button>
                <input type="file" id="file-input" accept=".epub" style="display: none">
              </li>
              <li>
                <form id="metadataForm" method="post" action="insert_metadatapj.php">
                  <input type="file" class="choisir-fichier" id="fileInput" webkitdirectory directory multiple
                    accept=".epub">
                  <div id="metadata">
                    <h2 style="display : none">Métadonnées </h2>
                    <p style="display : none" id="title">Titre : </p>
                    <p style="display : none" id="creator">Auteur : </p>
                    <p style="display : none" id="language">Language : </p>
                    <p style="display : none" id="subject">Subject : </p>
                    <p style="display : none" id="publisher">Publisher : </p>
                    <p style="display : none" id="lienfiles">Lienfiles : </p>
                    <p style="display : none" id="lienfolder">Lienfolder : </p>

                    <!-- Ajoutez d'autres balises HTML pour afficher d'autres métadonnées si nécessaire -->
                  </div>
                </form>
              </li>
            </ul>
          </li>
          <li id="header_convertir_livre_bar" class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">
              Recupération des actualités
              <i class="fa-solid fa-newspaper"></i>
            </a>
            <ul class="dropdown-menu extended notification">
              <div class="notify-arrow notify-arrow-green"></div>
              <li>
                <a href="index.html#">
                  <span class="label label-danger"><i class="fa fa-calendar"></i></span>
                  Charger les actualités
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="top-menu">
        <ul class="nav pull-right top-menu">
          <?php if (isset($_SESSION['username'])): ?>
            <!-- Utilisateur connecté -->
            <li><a class="logout"
                href="./pages_cnx/logout.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Déconnexion</a>
            </li>
          <?php else: ?>
            <!-- Utilisateur non connecté -->
            <li><a class="logout" href="./pages_cnx/login.php">Se Connecter</a></li>
          <?php endif; ?>
        </ul>
      </div>
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
          <li class="sub-menu">
            <a href="#" id="biblioCommuneLink" class="menu-link">
              <i class="fa fa-book"></i>
              <span>Bibliothèque Commune</span>
            </a>
            <ul id="menuDeroulantCommun" class="menu-deroulant-commune" style="display: block;">
              <li class="auteur">
                <a href="./index.php" class="active">
                  <i class="fa fa-book-open"></i>
                  <span>Livres</span>
                </a>
              </li>
              <li class="auteur">
                <a href="./pages_commune/auteurs_commune.php">
                  <i class="fa fa-user-tie"></i>
                  <span>Auteurs</span>
                </a>
              </li>
              <li class="editeur">
                <a href="./pages_commune/editeurs_commune.php">
                  <i class="fa fa-feather"></i>
                  <span>Editeurs</span>
                </a>
              </li>
              <li class="Genres">
                <a href="./pages_commune/genres_commune.php">
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
                <a href="./pages_perso/livres_perso.php">
                  <i class="fa fa-book-open"></i>
                  <span>Livres</span>
                </a>
              </li>
              <li class="auteur">
                <a href="./pages_perso/auteurs_perso.php">
                  <i class="fa fa-user-tie"></i>
                  <span>Auteurs</span>
                </a>
              </li>
              <li class="editeur">
                <a href="./pages_perso/editeurs_perso.php">
                  <i class="fa fa-feather"></i>
                  <span>Editeurs</span>
                </a>
              </li>
              <li class="Genres">
                <a href="./pages_perso/genres_perso.php">
                  <i class="fa fa-tags"></i>
                  <span>Genres</span>
                </a>
              </li>
            </ul>
          </li>
          <li class="sub-menu">
            <a href="./pages_autres/livresRecherche.php">
              <i class="fa fa-globe"></i>
              <span>Recherche d'Ebook</span>
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
              <h3>BIBLIOTHEQUE COMMUNE</h3>
              <?php
              try {
                $connexion = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Récupérer le terme de recherche depuis l'URL
                $recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';

                // Requête pour récupérer les livres filtrés par le terme de recherche
                $requete = "SELECT livre.id AS id, livre.lienfiles AS lien, livre.lienfolder AS nomfichier, livre.nom AS nom, auteur.nom AS auteur, editeur.nom AS editeur, genre.nom AS genre, langue.nom AS langue
                                  FROM livre 
                                  JOIN auteur ON livre.idauteur = auteur.id 
                                  JOIN editeur ON livre.idediteur = editeur.id 
                                  JOIN genre ON livre.idgenre = genre.id 
                                  JOIN langue ON livre.idlangue = langue.id 
                                  WHERE livre.nom LIKE :recherche";
                                  

                $stmt = $connexion->prepare($requete);
                $stmt->bindValue(':recherche', "%$recherche%", PDO::PARAM_STR);
                $stmt->execute();

                $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
              } catch (PDOException $e) {
                echo "Erreur de connexion : " . $e->getMessage();
              }

              // Affichage du formulaire de recherche
              $formulaireRecherche = '
                      <form class="barre-recherche" action="" method="GET">
                          <input type="text" name="recherche" placeholder="Rechercher un livre" value="' . htmlspecialchars($recherche) . '">
                          <input type="submit" value="Rechercher">
                      </form>';
              echo $formulaireRecherche;

              // Affichage des livres filtrés
              echo '<div class="container">';
              foreach ($livres as $livre) {
                echo '<div class="book">';
                echo '<div class="title-bar">';
                echo '<h2>' . (isset($livre['nom']) ? htmlspecialchars($livre['nom']) : 'Inconnu') . '</h2>';
                echo '<div id="header_ajout_livre_bar" class="dropdown bars">';
                echo '<a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">';
                echo '<i class="fa-solid fa-bars"></i>';
                echo '</a>';
                echo '<ul class="dropdown-menu extended notification">';
                echo '<div class="notify-arrow notify-arrow-grey"></div>';
                echo '<li>';
                echo '<a href="./pages_autres/visualiser.php?nomfichier=' . urlencode($livre['nomfichier']) . '"><i class="fa fa-eye"></i> Visualiser</a>';
                echo '</li>';
                echo '<li>';
                echo '<a href="#" onclick="lireMetadonnees(\'' . htmlspecialchars($livre['lien']). '\',\'' . htmlspecialchars($livre['langue']) . '\',\'' . htmlspecialchars($livre['id']). '\',\'' . htmlspecialchars($livre['auteur']). '\',\'' . htmlspecialchars($livre['nom']). '\',\'' . htmlspecialchars($livre['editeur']). '\',\'' . htmlspecialchars($livre['genre']) . '\' )"><i class="fa fa-pencil"></i> Modifier le livre : ' . (isset($livre['id']) ? htmlspecialchars($livre['id']) : 'Inconnu') . '</a>';
                echo '</li>';
                echo '<li>';
                echo '<a href="#"><i class="fa fa-arrows-rotate"></i> Convertir</a>';
                echo '</li>';
                echo '<li>';
                echo '<a href="#" onclick="confirmDelete(' . $livre['id'] . ')"><i class="fa fa-trash"></i> Supprimer</a>';
                echo '</li>';
                echo '</ul>';
                echo '</div>';
                echo '</div>';
                echo '<p><strong>Auteur :</strong> ' . (isset($livre['auteur']) ? htmlspecialchars($livre['auteur']) : 'Inconnu') . '</p>';
                echo '<p><strong>Éditeur :</strong> ' . (isset($livre['editeur']) ? htmlspecialchars($livre['editeur']) : 'Inconnu') . '</p>';
                echo '<p><strong>Genre :</strong> ' . (isset($livre['genre']) ? htmlspecialchars($livre['genre']) : 'Inconnu') . '</p>';
                echo '<p><strong>Langue :</strong> ' . (isset($livre['langue']) ? htmlspecialchars($livre['langue']) : 'Inconnu') . '</p>';
                echo '</div>';
              }

              echo '</div>';
              ?>
            </div>
            <!--custom chart end-->
          </div>
          <!-- /col-lg-3 -->
        </div>
        <!-- /row -->
      </section>
    </section>
    <!--main content end-->
    <?php
          // Informations de connexion à la base de données
          $servername = "localhost"; // Remplacez par le nom de votre serveur de base de données
          $username = "root"; // Remplacez par votre nom d'utilisateur de base de données
          $password = ""; // Remplacez par votre mot de passe de base de données
          $database = "Biblio"; // Remplacez par le nom de votre base de données

          try {
              // Création de la connexion à la base de données
              $connexion = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
              // Définition des attributs de PDO pour gérer les erreurs
              $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              // Récupération des auteurs
              $query_auteurs = "SELECT id, nom FROM auteur";
              $result_auteurs = $connexion->query($query_auteurs);
              $auteurs = $result_auteurs->fetchAll(PDO::FETCH_ASSOC);

              // Récupération des éditeurs
              $query_editeurs = "SELECT id, nom FROM editeur";
              $result_editeurs = $connexion->query($query_editeurs);
              $editeurs = $result_editeurs->fetchAll(PDO::FETCH_ASSOC);

              // Récupération des genres
              $query_genres = "SELECT id, nom FROM genre";
              $result_genres = $connexion->query($query_genres);
              $genres = $result_genres->fetchAll(PDO::FETCH_ASSOC);

              // Récupération des langues
              $query_langues = "SELECT id, nom FROM langue";
              $result_langues = $connexion->query($query_langues);
              $langues = $result_langues->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
              // En cas d'erreur de connexion à la base de données
              echo "Erreur de connexion : " . $e->getMessage();
          }
      ?>

    <div id="metadata-form">
    <h3>Formulaire des métadonnées</h3>

    <div>
        <label for="TXT_Titre">Titre du livre :</label>
        <input type="text" id="TXT_Titre" name="TXT_Titre">
    </div>
    <div>
        <label for="TXT_Auteur">Nom de l'auteur actuel :</label>
        <imput type="text" id="TXT_Auteur" name="TXT_Auteur">
    </div>
    <div>
        <label for="selectAuteur">Modification Auteur :</label>
        <select id="selectAuteur" name="selectAuteur">
            <?php foreach ($auteurs as $auteur) : ?>
                <option value="<?php echo $auteur['id']; ?>"><?php echo $auteur['nom']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="TXT_Editeur">Nom de l'Éditeur actuel :</label>
        <imput type="text" id="TXT_Editeur" name="TXT_Editeur">
    </div>
    <div>
        <label for="selectEditeur">Modification Editeur :</label>
        <select id="selectEditeur" name="selectEditeur">
            <?php foreach ($editeurs as $editeur) : ?>
                <option value="<?php echo $editeur['id']; ?>"><?php echo $editeur['nom']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="TXT_Genre">Nom du Genre actuel :</label>
        <imput type="text" id="TXT_Genre" name="TXT_Genre">
    </div>
    <div>
        <label for="selectGenre">Modification du Genre :</label>
        <select id="selectGenre" name="selectGenre">
            <?php foreach ($genres as $genre) : ?>
                <option value="<?php echo $genre['id']; ?>"><?php echo $genre['nom']; ?></option>
            <?php endforeach; ?>
            <br>
        </select>
    </div>

    <div>
        <label for="TXT_Langue">Nom de la Langue actuel :</label>
        <imput type="text" id="TXT_Langue" name="TXT_Langue">
    </div>
    <div>
        <label for="selectLangue">Modification de la Langue :</label>
        <select id="selectLangue" name="selectLangue" >
            <?php foreach ($langues as $langue) : ?>
                <option value="<?php echo $langue['id']; ?>"><?php echo $langue['nom']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <script>
        document.getElementById('selectAuteur').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const nomAuteur = selectedOption.textContent;
            document.getElementById('TXT_Auteur').value = nomAuteur;
        });

        document.getElementById('selectEditeur').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const nomEditeur = selectedOption.textContent;
            document.getElementById('TXT_Editeur').value = nomEditeur;
        });

        document.getElementById('selectGenre').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const nomGenre = selectedOption.textContent;
            document.getElementById('TXT_Genre').value = nomGenre;
        });

        document.getElementById('selectLangue').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const nomLangue = selectedOption.textContent;
            document.getElementById('TXT_Langue').value = nomLangue;
        });
    </script>
    <button onclick="modifierMetadonnees()">Modifier</button>
    <button onclick="closeForm()">Fermer</button>
</div>


    <button onclick="modifierMetadonnees()">Modifier</button>
    <button onclick="closeForm()">Fermer</button>
</div>

    <script>
      function confirmDelete(bookId) {
        var confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce livre ?");

        if (confirmation) {
          window.location.href = './php_sql/deletebook.php?id=' + bookId;
        } else {
          // L'utilisateur a annulé la suppression
          // Vous pouvez ajouter un message ou effectuer d'autres actions si nécessaire
        }
      }

      function openForm() {
        var form = document.getElementById('metadata-form');
        form.style.display = 'block';
      }

      function closeForm() {
        document.getElementById('metadata-form').style.display = 'none';
      }

      let lienGlobal = ''; // Variable globale pour stocker la valeur de lien
      let IdGlobal = 0;

      function lireMetadonnees(lien, langue,id, auteur, nom, editeur, genre ) {
        lienGlobal = lien;
        IdGlobal = id;
   
     
        document.getElementById('TXT_Titre').value = nom || '';

        // Récupération de l'élément label
        let labelAuteur = document.getElementById('TXT_Auteur');
        labelAuteur.textContent = auteur || '';

        let labelEditeur = document.getElementById('TXT_Editeur');
        labelEditeur.textContent = editeur || '';

        let labelGenre = document.getElementById('TXT_Genre');
        labelGenre.textContent = genre || '';
    
        let labelLangue = document.getElementById('TXT_Langue');
        labelLangue.textContent = langue || '';
        // Afficher le formulaire une fois les métadonnées chargées
        document.getElementById('metadata-form').style.display = 'block';
    
    
}


function modifierMetadonnees() {
    const titre = document.getElementById('TXT_Titre').value;
    const auteur = document.getElementById('selectAuteur').value;
    const editeur = document.getElementById('selectEditeur').value;
    const langue = document.getElementById('selectLangue').value;
    const genre = document.getElementById('selectGenre').value;

    // Récupération des noms correspondant aux ID sélectionnés
    const auteurNom = document.getElementById('selectAuteur').options[document.getElementById('selectAuteur').selectedIndex].text;
    const editeurNom = document.getElementById('selectEditeur').options[document.getElementById('selectEditeur').selectedIndex].text;
    const langueNom = document.getElementById('selectLangue').options[document.getElementById('selectLangue').selectedIndex].text;
    const genreNom = document.getElementById('selectGenre').options[document.getElementById('selectGenre').selectedIndex].text;

    console.log('Nom de l\'auteur sélectionné :', auteurNom);
    console.log('Nom de l\'éditeur sélectionné :', editeurNom);
    console.log('Nom de la langue sélectionnée :', langueNom);
    console.log('Nom du genre sélectionné :', genreNom);

    const data = {
        id: IdGlobal,
        titre: titre,
        auteur: auteur,
        editeur: editeur,
        langue: langue,
        genre: genre,
        lienGlobal: lienGlobal,
        auteurNom: auteurNom, // Ajout des noms correspondants aux ID sélectionnés
        editeurNom: editeurNom,
        langueNom: langueNom,
        genreNom: genreNom
        // Ajoutez d'autres données si nécessaire
    };

    // Envoi des données à modifier_metadonneesFichier.php
    fetch('./fonctions_php/modifier_metadonneesFichier.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (response.ok) {
            console.log('Mise à jour du fichier réussie !');
            // Appel de la nouvelle page PHP pour effectuer des modifications dans la base de données
            return fetch('./php_sql/modifier_metadonneesBDD.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data) // Vous pouvez envoyer les mêmes données ou adapter en fonction de vos besoins
            });
        } else {
            throw new Error('La mise à jour du fichier a échoué.');
        }
    })
    .then(response => {
        if (response.ok) {
            console.log('Mise à jour dans la base de données réussie !');
            window.location.reload();
        } else {
            throw new Error('La mise à jour dans la base de données a échoué.');
        }
    })
    .catch(error => {
        console.error('Erreur lors de la mise à jour :', error);
    })
    .finally(() => {
        document.getElementById('metadata-form').style.display = 'none';
    });
}
    </script>


    <script>

      const addBookButton = document.getElementById('add-book');
      const fileInput = document.getElementById('file-input');

      addBookButton.addEventListener('click', () => {
        fileInput.click();
      });

      // Ajoutez cette fonction pour gérer l'ajout de livre avec succès
      fileInput.addEventListener('change', (event) => {
        const files = event.target.files;
        if (files.length > 0) {
          const file = files[0];
          const formData = new FormData();
          formData.append('epubFile', file);

          fetch('./fonctions_php/ upload.php', {
            method: 'POST',
            body: formData
          })
            .then(response => response.json())
            .then(data => {
              // Gérer les données de réponse si nécessaire
              console.log(data);
              // Pour exemple, vous pouvez appeler addBookSuccess() après un ajout réussi
              // Afficher un message de succès à l'utilisateur
              alert('Livre ajouté avec succès !');
            })
            .catch(error => {
              console.error('Erreur lors de l\'ajout du livre :', error);
              // Afficher un message d'erreur à l'utilisateur
              alert('Erreur lors de l\'ajout du livre.');
            });
        }
      });
    </script>

    <script>
      // Choisi un fichier decompresser au préalab pour afficher les meta donnée est les ajouter dans la base
      document.getElementById('fileInput').addEventListener('change', handleFileSelect);
      async function getFileContent(filePath) {
        const response = await fetch(filePath);
        return response.blob();
      }

      let folderPath; // Ajoutez cette ligne pour déclarer la variable folderPath

      function handleFileSelect(event) {
        const files = event.target.files;

        if (files.length > 0) {
          const file = findOpfFile(files);

          if (file) {
            folderPath = file.webkitRelativePath.split('/').slice(0, -2).join('/'); // Mise à jour de folderPath
            const reader = new FileReader();

            reader.onload = async function (e) {
              const content = e.target.result;

              // Extraire les métadonnées
              const title = extractMetadata(content, 'title');
              const creator = extractMetadata(content, 'creator');
              const language = extractMetadata(content, 'language');
              const subject = extractMetadata(content, 'subject');
              const publisher = extractMetadata(content, 'publisher');
              // Afficher les métadonnées sur le site
              document.getElementById('title').textContent = `Titre : ${title}`;
              document.getElementById('creator').textContent = `Auteur : ${creator}`;
              document.getElementById('language').textContent = `Language : ${language}`;
              document.getElementById('subject').textContent = `Subject : ${subject}`;
              document.getElementById('publisher').textContent = `Publisher : ${publisher}`;

              const filesPath = file.webkitRelativePath;
              document.getElementById('lienfiles').textContent = `Lienfiles : ${filesPath}`;

              const folderWithoutSubfolders = folderPath.split('/').slice(0, -2).join('/');
              document.getElementById('lienfolder').textContent = `Lienfolder : ${folderPath}`;

              // Extraire le chemin de la couverture à partir de la balise meta
              // Afficher la couverture s'il y a un chemin
              insertIntoDatabase(title, creator, language, subject, publisher, filesPath, folderPath);
            };

            reader.readAsText(file);
          } else {
            alert("Fichier OPF introuvable dans le dossier EPUB.");
          }
        }
      }



      // chercher le fichier OPF dans le dossier selectionner
      function findOpfFile(files) {
        for (const file of files) {
          if (file.name.toLowerCase().endsWith('.opf')) {
            return file;
          } else if (file.isDirectory) {
            const opfFile = findOpfFile(file.webkitGetAsEntry().createReader().readEntries());
            if (opfFile) {
              return opfFile;
            }
          }
        }
        return null;
      }
      // une fois le fichier OPF trouver affiche les metadonnée 
      function extractMetadata(content, key) {
        const regex = new RegExp(`<dc:${key}.*?>(.*?)<\/dc:${key}>`);
        const match = content.match(regex);

        if (match) {
          console.log(`Match trouvé pour ${key}:`, match[1]);
          return match[1];
        } else {
          console.log(`Aucun match trouvé pour ${key}`);
          return 'Non trouvé';
        }
      }
      // une fois les metadonnée afficher ils sont envoyer dans la BDD
      function insertIntoDatabase(title, creator, language, subject, publisher, filesPath, folderWithoutSubfolders) {
        // Extraire le chemin du dossier sans la suite du chemin


        console.log('Chemin du dossier :', folderWithoutSubfolders);
        console.log('Chemin du fichier complet :', filesPath);
        const formData = new FormData();
        formData.append('nom', title);
        formData.append('auteur', creator);
        formData.append('langue', language);
        formData.append('genre', subject);
        formData.append('editeur', publisher);
        formData.append('lienfiles', filesPath);
        formData.append('lienfolder', folderWithoutSubfolders); // Ajoutez le chemin du dossier au FormData

        // Ajoutez d'autres métadonnées si nécessaire

        fetch('./php_sql/insert_metadatapj.php', {
          method: 'POST',
          body: formData
        })
          .then(response => {
            if (response.ok) {
              console.log('Métadonnées insérées avec succès dans la base de données.');
              window.location.reload();
            } else {
              console.error('Erreur lors de l\'insertion des métadonnées.');
            }
          })
          .catch(error => {
            console.error('Erreur : ', error);
          });
      }

    </script>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="lib/jquery/jquery.min.js"></script>

    <script src="lib/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>