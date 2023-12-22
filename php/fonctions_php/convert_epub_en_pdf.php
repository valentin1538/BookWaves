<?php

require '../lib/vendor/autoload.php';

// Vérifier si un fichier EPUB est téléchargé
if (isset($_FILES['epubFile']) && $_FILES['epubFile']['error'] === UPLOAD_ERR_OK) {
    $epubFile = $_FILES['epubFile']['tmp_name'];
    $tempDir = '../lib/kio';

    // 1. Créer le dossier temporaire "kio" s'il n'existe pas
    if (!is_dir($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    // Ouvrir le fichier EPUB avec ZipArchive
    $zip = new ZipArchive;
    if ($zip->open($epubFile) === true) {
        // 2. Extraire les fichiers dans le dossier temporaire "kio"
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $fileName = $zip->getNameIndex($i);
            $fileContent = $zip->getFromIndex($i);

            // Créer le chemin complet pour le fichier dans le dossier temporaire "kio"
            $filePath = $tempDir . '/' . $fileName;

            // Vérifier si le chemin correspond à un fichier
            if (!is_dir($filePath)) {
                // Créer les sous-dossiers si nécessaire
                $dir = dirname($filePath);
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                // Écrire le contenu du fichier si $fileContent n'est pas vide
                if (!empty($fileContent)) {
                    file_put_contents($filePath, $fileContent);
                }
            }
        }

        // Fermer le fichier EPUB
        $zip->close();

        // Trouver les fichiers XHTML, CSS, JPG dans les sous-dossiers du dossier temporaire "kio"
        $xhtmlFiles = glob($tempDir . '/**/*.xhtml', GLOB_BRACE);
        $cssFiles = glob($tempDir . '/**/*.css', GLOB_BRACE);
        $jpgFiles = glob($tempDir . '/**/*.jpg', GLOB_BRACE);

        // Générer le fichier PDF
        $pdfFile = '../lib/Librairy/' . pathinfo($_FILES['epubFile']['name'], PATHINFO_FILENAME) . '.pdf';
        $mpdf = new \Mpdf\Mpdf();

        // Appliquer le CSS pour chaque fichier CSS trouvé
        foreach ($cssFiles as $cssFile) {
            $content = file_get_contents($cssFile);
            $mpdf->WriteHTML('<style>' . $content . '</style>');
        }

        // Ajouter les fichiers XHTML et JPG au PDF
        foreach ($xhtmlFiles as $xhtmlFile) {
            $content = file_get_contents($xhtmlFile);
            $mpdf->WriteHTML($content);
            $mpdf->AddPage();
        }

        foreach ($jpgFiles as $jpgFile) {
            $mpdf->Image($jpgFile, 10, 10); // Vous pouvez ajuster les coordonnées X et Y
        }

        // 3. Supprimer le dossier temporaire "kio"
        removeDirectory($tempDir);

        // Enregistrer le PDF
        $mpdf->Output($pdfFile, 'F');

        // Envoyer une réponse indiquant que la conversion est terminée avec succès
        echo 'Success | Conversion en PDF terminée. Téléchargez votre <a href="' . $pdfFile . '" target="_blank">PDF ici</a>.';
    } else {
        // Envoyer une réponse en cas d'échec d'ouverture du fichier EPUB
        echo 'Erreur | Impossible d\'ouvrir le fichier EPUB.';
    }
} else {
    // Envoyer une réponse en cas d'absence de fichier EPUB téléchargé
    echo 'Erreur | Aucun fichier EPUB téléchargé.';
}

// Fonction pour supprimer un dossier et son contenu récursivement
function removeDirectory($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . "/" . $object)) {
                    removeDirectory($dir . "/" . $object);
                } else {
                    unlink($dir . "/" . $object);
                }
            }
        }
        rmdir($dir);
    }
}
?>
