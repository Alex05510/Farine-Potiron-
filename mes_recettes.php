<?php 
/** controleur de page mes recette
 * role recuperer les recettes de l'utilisateur connecté
 * parametre : $user_id  l'identifiant de l'utilisateur pour recuperer ses recettes
 */

include "library/init.php";


if (!moi()) {
    // on controle si l'utilisateur est connecté sinon redirect vers login
    header("Location: login.php");
    exit;
}

// Récupération des recettes de l'utilisateur
$recette = new Recette();
$recettes = $recette->listByUser($_SESSION['user_id']);
if (!is_array($recettes)) {
    $recettes = [];
}

include "templates/pages/afficher_mes_recettes.php";