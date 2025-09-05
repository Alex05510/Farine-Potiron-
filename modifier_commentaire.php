<?php
/** Controleur pour modifier un commentaire */
include "library/init.php";

// Vérifier que l'utilisateur est connecté
$user = moi();
if (!$user) {
    header("Location: connexion.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = "";
$commentaire = null;

if ($id > 0) {
    $commentaireObj = new Commentaire();
    $commentaireObj->loadFromId($id);
    if ($commentaireObj->is() && $commentaireObj->get('user')->id() == $user->id()) {
        $commentaire = $commentaireObj;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenu = trim($_POST['contenu'] ?? '');
            
            if (!empty($contenu)) {
                $commentaireObj->updateContenu($contenu);
                $message = "Commentaire modifié avec succès.";
            } else {
                $message = "Le contenu ne peut pas être vide.";
            }
        }
    } else {
        $message = "Accès refusé ou commentaire introuvable.";
    }
} else {
    $message = "Aucun commentaire sélectionné.";
}

include "templates/pages/form_modifier_commentaire.php";