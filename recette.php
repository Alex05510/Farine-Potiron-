<?php 
/** Controleur de la page recette
 * role : afficher les informations d'une recette
 * param : id de la recette à afficher
 */

include "library/init.php";

$message = "";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$recette = null;
if ($id > 0) {
    $recetteObj = new Recette();
    $recetteObj->loadFromId($id);
    if ($recetteObj->is()) {
        $recette = $recetteObj;
    } else {
        $message = "Recette non trouvée.";
    }
} else {
    $message = "Aucune recette sélectionnée.";
}

// on recupere les commentaire des recettes
$commentaires = [];
if ($recette) {
    $commentaireObj = new Commentaire();
    $commentaires = $commentaireObj->getByRecette($recette->get('id'));
}

if (!isset($note)) {
    $note = new Note();
}
$user_id = $_SESSION['user_id'] ?? null;
$recette_id = $recette ? $recette->id() : null;


// Gestion de plusieurs farines utilisées
$refs = array_map('trim', explode(',', $recette->get('farine')));
$farines_info = [];

$json = file_get_contents('https://api.mywebecom.ovh/play/fep/catalogue.php');
$farines = json_decode($json, true);

foreach ($refs as $ref) {
    if (!empty($ref)) {
        $nomFarine = isset($farines[$ref]) ? $farines[$ref] : $ref;
        $detail_url = 'https://api.mywebecom.ovh/play/fep/catalogue.php?ref=' . urlencode($ref);
        $detail_json = file_get_contents($detail_url);
        $detail = json_decode($detail_json, true);
        $descriptionFarine = isset($detail['description']) ? $detail['description'] : '';
        $farines_info[] = [
            'nom' => $nomFarine,
            'description' => $descriptionFarine
        ];
    }
}

include "templates/pages/afficher_recette.php";