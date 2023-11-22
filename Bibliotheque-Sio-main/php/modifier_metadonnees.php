<?php
// Récupérer les données envoyées depuis JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Récupérer les valeurs des métadonnées à partir des données
$titre = $data['titre'];
$auteur = $data['auteur'];
// Récupérer d'autres données si nécessaire

// Chemin vers le fichier OPF
$chemin_fichier = 'lib/Librairy/flaubert_correspondance_tome_III/OPS/content.opf';

// Lire le contenu du fichier OPF
$contenu = file_get_contents($chemin_fichier);

// Modifier les métadonnées dans le contenu du fichier $contenu
// Par exemple, vous pouvez utiliser des expressions régulières pour localiser et remplacer les métadonnées

// Écrire le contenu modifié dans le fichier OPF
file_put_contents($chemin_fichier, $contenu);

// Réponse indiquant le succès ou l'échec de la modification
http_response_code(200); // Réponse OK
?>