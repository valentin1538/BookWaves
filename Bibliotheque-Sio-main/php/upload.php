<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $targetDirectory = 'lib/Librairy/';
  $targetFile = $targetDirectory . basename($_FILES['epubFile']['name']);
  $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // Vérifier si le fichier est un fichier EPUB
  if ($fileType !== 'epub') {
    echo json_encode(['error' => 'Seuls les fichiers EPUB sont autorisés.']);
    exit();
  }

  // Déplacer le fichier vers le dossier Librairy
  if (move_uploaded_file($_FILES['epubFile']['tmp_name'], $targetFile)) {
    $zip = new ZipArchive();
    if ($zip->open($targetFile) === TRUE) {
      $extractPath = $targetDirectory . pathinfo($targetFile, PATHINFO_FILENAME);
      $zip->extractTo($extractPath);
      $zip->close();

      echo json_encode(['success' => 'Fichier EPUB téléchargé et décompressé avec succès.']);
    } else {
      echo json_encode(['error' => 'Erreur lors de l\'ouverture du fichier EPUB.']);
    }
  } else {
    echo json_encode(['error' => 'Erreur lors du téléchargement du fichier EPUB.']);
  }
} else {
  echo json_encode(['error' => 'Méthode non autorisée.']);
}
?>
