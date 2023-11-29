<?php
// Récupérer les données envoyées depuis JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Récupérer les valeurs des métadonnées à partir des données
$titre = $data['titre'];
$auteur = $data['auteur'];
$editeur = $data['editeur'];
$langue = $data['langue'];
$lienGlobal = $data['lienGlobal']; // Récupération de la valeur de lienfile

// Chemin vers le fichier OPF en fonction de la valeur de lienfile
$chemin_fichier = "./lib/Librairy/" . $lienGlobal . "";

// Charger le contenu XML du fichier OPF dans un objet DOMDocument
$doc = new DOMDocument();
$doc->load($chemin_fichier);

// Mettre à jour le titre
$titles = $doc->getElementsByTagName('title');
foreach ($titles as $title) {
    $title->nodeValue = $titre;
}

// Mettre à jour le créateur (auteur)
$creators = $doc->getElementsByTagName('creator');
foreach ($creators as $creator) {
    $creator->nodeValue = $auteur;
}

// Mettre à jour l'éditeur
$publishers = $doc->getElementsByTagName('publisher');
foreach ($publishers as $publisher) {
    $publisher->nodeValue = $editeur;
}

// Mettre à jour la langue
$languages = $doc->getElementsByTagName('language');
foreach ($languages as $language) {
    $language->nodeValue = $langue;
}

// Sauvegarder les modifications dans le fichier OPF
$doc->save($chemin_fichier);

// Réponse indiquant le succès de la modification
http_response_code(200); // Réponse OK
?>