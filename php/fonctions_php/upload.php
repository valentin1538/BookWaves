<!-- SOUS PROJET ACHILLE -->

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $targetDirectory = '../lib/Librairy/';
  $fileType = strtolower(pathinfo($_FILES['epubFile']['name'], PATHINFO_EXTENSION));

  // Vérifier si le fichier est un fichier EPUB
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $fileMimeType = finfo_file($finfo, $_FILES['epubFile']['tmp_name']);
  finfo_close($finfo);

  if ($fileMimeType !== 'application/zip' && $fileType !== 'epub') {
    echo json_encode(['error' => 'Seuls les fichiers EPUB sont autorisés.']);
    exit();
  }

  // Créer un sous-dossier avec le nom du fichier (sans extension)
  $epubFileName = pathinfo($_FILES['epubFile']['name'], PATHINFO_FILENAME);
  $subdirectory = $targetDirectory . $epubFileName . '/';

  // Vérifier si le dossier existe déjà
  if (!is_dir($subdirectory)) {
    mkdir($subdirectory, 0777, true);
  } else {
    // Si le dossier existe déjà, renommer le dossier temporairement pour éviter les conflits
    $timestamp = time();
    $subdirectory .= $epubFileName . '_' . $timestamp . '/';
    mkdir($subdirectory, 0777, true);
  }

  // Déplacer le fichier EPUB directement dans le sous-dossier de destination
  move_uploaded_file($_FILES['epubFile']['tmp_name'], $subdirectory . $_FILES['epubFile']['name']);

  // Extraire le contenu de l'EPUB dans le sous-dossier
  $zip = new ZipArchive();
  if ($zip->open($subdirectory . $_FILES['epubFile']['name']) === TRUE) {
    $zip->extractTo($subdirectory);
    $zip->close();

    echo json_encode(['success' => 'Fichier EPUB téléchargé et décompressé avec succès.']);
  } else {
    echo json_encode(['error' => 'Erreur lors de l\'ouverture du fichier EPUB.']);
  }
} else {
  echo json_encode(['error' => 'Méthode non autorisée.']);
}
?>