<?php 
/** Controleur de profil
 * role : gérer le profil utilisateur
 */

include "library/init.php";

$message = "";
$messageSuccess = "";
$messageSuccessImg = "";

if (!moi()) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$req = $bdd->prepare('SELECT * FROM User WHERE id = ?');
$req->execute([$user_id]);
$user = $req->fetch();

if (!$user) {
    $message = 'Utilisateur non trouvé.';
}

// Traitement du formulaire de mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'] ?? '';
    $email = $_POST['email'] ?? '';

    if (isset($_POST['enregistrer'])) {
        // Mettre à jour en BDD
        bddUpdate("User", [
            "pseudo" => $pseudo,
            "email" => $email
        ], $user['id']);
        // Mettre à jour $user
        $user['pseudo'] = $pseudo;
        $user['email'] = $email;
        $messageSuccess = "Profil mis à jour avec succès.";
    }

    // Traitement de la photo de profil
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['image']['size'] <= 2097152) {
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $nouveauNom = 'profil_' . $user['id'] . '.' . $extension;
                $photoPath = 'assets/images/' . $nouveauNom;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $photoPath)) {
                // Met à jour le chemin de la photo en BDD
                bddUpdate("User", [
                    "image" => $photoPath
                ], $user['id']);
                $user['image'] = $photoPath;
                $messageSuccessImg .= "Photo de profil mise à jour !";
            } else {
                $message .= "Erreur lors de l'enregistrement de la photo.";
            }
        } else {
            $message .= "Le fichier photo est trop volumineux (max 2 Mo).";
        }
    }
}

include "templates/pages/afficher_profil.php";