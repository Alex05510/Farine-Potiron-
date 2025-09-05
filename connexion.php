<?php
    /** controleur de la page de connexion
     * role : préparer le template de la page de connexion
     */
    
    include "library/init.php";

// Affichage du message de succès après inscription
if (isset($_SESSION['messageSuccess'])) {
    $messageSuccess = $_SESSION['messageSuccess'];
    unset($_SESSION['messageSuccess']);
}



$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // on vérifie que le champ "pot de miel" est vide (anti-spam)
    if (isset($_POST['raison']) && empty($_POST['raison'])) {
        // on vérifie que les champs sont présents et remplis
        $identifiant = isset($_POST["identifiant"]) ? trim($_POST["identifiant"]) : "";
        $mdp = isset($_POST["mdp"]) ? $_POST["mdp"] : "";
        if (!empty($identifiant) && !empty($mdp)) {
            // Vérifie si c'est un email
            if (filter_var($identifiant, FILTER_VALIDATE_EMAIL)) {
            $userObj = new User();
            $userObj->getByEmail($identifiant);
        } else {
            // Si ce n'est pas un email, on cherche par pseudo
            $userObj = new User();
            $userObj->getByPseudo($identifiant); 
        }
        // on vérifie que l'utilisateur existe et que le mot de passe est correct
        if ($userObj->is() && password_verify($mdp, $userObj->get('mdp'))) {
            $_SESSION['user_id'] = $userObj->id();
            header("Location: index.php?connexion=success");
            // si connexion ok on redirige vers la page d'accueil sinon un message d'erreur
            exit;
        } else {
            $message = "Identifiants incorrects.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
}
include "templates/pages/form_connexion.php";