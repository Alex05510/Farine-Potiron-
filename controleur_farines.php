<?php

// Récupérer le catalogue complet depuis l'API
$url = 'https://api.mywebecom.ovh/play/fep/catalogue.php';
$json = file_get_contents($url);
$farines = json_decode($json, true);

if (!$farines) {
    die('Erreur lors de la récupération du catalogue.');
}

// Affichage simple 
echo '<h2>Nos farines spéciales</h2>';
echo '<ul>';
foreach ($farines as $ref => $libelle) {
    echo '<li>' . htmlspecialchars($libelle) . ' <small>(' . htmlspecialchars($ref) . ')</small></li>';
}
echo '</ul>';

// Afficher le détail d'une farine 
$ref = $recette['farine'];

// Charger le catalogue pour obtenir le nom
$json = file_get_contents('https://api.mywebecom.ovh/play/fep/catalogue.php');
$farines = json_decode($json, true);
$nomFarine = isset($farines[$ref]) ? $farines[$ref] : $ref;

// Charger le détail pour obtenir la description
$detail_url = 'https://api.mywebecom.ovh/play/fep/catalogue.php?ref=' . urlencode($ref);
$detail_json = file_get_contents($detail_url);
$detail = json_decode($detail_json, true);
$descriptionFarine = isset($detail['description']) ? $detail['description'] : '';

if (isset($detail['reference'])) {
    echo '<h3>Détail de la farine ' . htmlspecialchars($detail['libelle']) . '</h3>';
    echo '<p>' . nl2br(htmlspecialchars($detail['description'])) . '</p>';
} else {
    echo '<p>Farine non trouvée.</p>';
}