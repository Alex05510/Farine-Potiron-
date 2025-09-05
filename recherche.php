<?php
/** controleur de recherche 
 * role : gérer la recherche de recettes
 * paramètres : néant
 */
include "library/init.php";

$mot = isset($_GET['search']) ? trim($_GET['search']) : '';
$recettes = [];

if (!empty($mot)) {
    // Recherche dans le titre, la description et les ingrédients
    $recette = new Recette();
    $recettes = $recette->search($mot);

    // Recherche d'un produit farine par référence exacte
    $farine_info = null;
    $url = "https://api.mywebecom.ovh/play/fep/catalogue.php?ref=" . urlencode($mot);
    $response = @file_get_contents($url);
    if ($response !== false) {
        $data = json_decode($response, true);
        if (isset($data['reference'])) {
            $farine_info = [
                'reference' => $data['reference'],
                'libelle' => $data['libelle'],
                'description' => $data['description']
            ];
        } elseif (isset($data['error']) && $data['error'] === 'not_found') {
            $farine_info = [
                'reference' => $mot,
                'libelle' => '',
                'description' => "Produit non trouvé pour la référence '" . htmlspecialchars($mot) . "'"
            ];
        }
    }
}

include "templates/pages/afficher_recherche.php";