<?php 
/** controleur de page de commentaire
 * role : ajouter un commentaire à une recette
 * param : id de la recette à commenter
 */



include "library/init.php";

// Message par défaut (vide au départ)
$message = "";

// Vérification si l'utilisateur est connecté, sinon redirection vers la page de login
if (!moi()) {
    header("Location: login.php");
    exit;
}

// Initialisation des variables si elles ne sont pas définies
if (!isset($recette)) $recette = null;
if (!isset($commentaires) || !is_array($commentaires)) $commentaires = [];

// Récupération et sécurisation des données envoyées par le formulaire
$recette_id = isset($_POST['recette_id']) ? intval($_POST['recette_id']) : 0; 
$contenu = isset($_POST['contenu']) ? trim($_POST['contenu']) : ""; 

// Vérification que l'ID de la recette est valide et que le commentaire n'est pas vide
if ($recette_id > 0 && !empty($contenu)) {
    // Création d'un nouvel objet Commentaire
    $commentaire = new Commentaire();
    
    // Remplissage des champs du commentaire
    $commentaire->set("recette", $recette_id);
    $commentaire->set("user", moi()->get('id')); 
    $commentaire->set("contenu", $contenu);
    $commentaire->set("date", date('Y-m-d H:i:s')); 
    
    // Sauvegarde du commentaire en base de données
    if ($commentaire->save()) {
        $message = "Commentaire ajouté avec succès.";
    } else {
        $message = "Erreur lors de l'ajout du commentaire.";
    }
} else {
    // Si les données reçues sont invalides
    $message = "Données invalides.";
}

include "templates/pages/afficher_recette.php";