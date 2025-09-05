<?php 
/** Controleur d'inscription
 * role : controle les données d'inscription et crée un nouvel utilisateur
 * parametre :
 *  $POST pour récupérer les données du formulaire
 *  $_SESSION pour stocker l'utilisateur connecté
 */

include "library/init.php"; 

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'] ?? '';
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    if (empty($pseudo) || empty($email) || empty($mdp)) {
        $message = "Tous les champs sont obligatoires.";
    } else {
        $user = new User();
        $id = $user->newUser($pseudo, $email, $mdp);
        if ($id) {
            $_SESSION['messageSuccess'] = "Inscription réussie ! Merci de vous connecter.";
            header("Location: connexion.php");
            exit;
        } else {
            $messageError = "Erreur lors de l'inscription.";
        }
    }
}

include "templates/pages/form_inscription.php";