<?php
// SOUS PROJET YOUNES

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si un fichier a été soumis
    if(isset($_FILES["pdfFile"]) && $_FILES["pdfFile"]["error"] == 0) {
        $pdfFile = $_FILES["pdfFile"]["tmp_name"];
        
        // Obtenir le nom du fichier PDF d'origine sans l'extension
        $pdfFileName = pathinfo($_FILES["pdfFile"]["name"], PATHINFO_FILENAME);

        // Spécifier le chemin complet pour le fichier EPUB de sortie
        $outputFile = "../lib/Librairy/$pdfFileName.epub";

        // Spécifier le chemin complet vers ebook-convert.exe
        $ebookConvertPath = "../lib/ebook-convert.exe";

        // Exécuter la commande ebook-convert.exe
        $command = "\"$ebookConvertPath\" \"$pdfFile\" \"$outputFile\"";
        exec($command);

        // Télécharger le fichier EPUB généré
        header("Content-Type: application/epub+zip");
        header("Content-Disposition: attachment; filename=" . basename($outputFile));
        readfile($outputFile);

        // Supprimer le fichier EPUB généré (facultatif, dépend de vos besoins)
        unlink($outputFile);
    } else {
        echo "Erreur lors de l'upload du fichier.";
    }
}
?>

