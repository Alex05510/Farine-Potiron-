<?php 
/** controleur de like 
 * rôle : permet de gérer les likes sur les recettes
 * param : $user_id l'identifiant de l'utilisateur, $recette_id l'identifiant de la recette
 */

include "library/init.php";


$user = moi();
$user_id = is_object($user) && method_exists($user, 'id') ? $user->id() : $user;
$recette_id = $_GET['id'];

if (isset($_GET['like'])) {
    $note = new Note();
    $note->addLike($user_id, $recette_id);
} elseif (isset($_GET['unlike'])) {
    $note = new Note();
    $note->removeLike($user_id, $recette_id);
}
header("Location: recette.php?id=" . $recette_id);