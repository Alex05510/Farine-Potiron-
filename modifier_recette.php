<?php 

/** controleur de page modifier recettes
 * role : modifier une recette existante en bdd
 * parametre : $id l'identifiant de la recette à modifier
 */

include "library/init.php";

$recetteObj = new Recette();
$recettes = $recetteObj->listByUser($_SESSION['user_id']);

// Récupérer la liste des ingrédients pour le formulaire
$ingredientObj = new Ingredient();
$listeIngredients = $ingredientObj->listAll();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $titre = $_POST["titre"];
    $description = $_POST["description"];
    $ingredient = $_POST["ingredient"];
    $duree = $_POST["duree"];
    $difficult = $_POST["difficult"];
    // Gestion de l'image
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['image']['size'] <= 2097152) { // 2 Mo max
            $imagePath = 'assets/images/' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $image = $imagePath;
            }
        }
    }

    // Validation des données
    if (empty($titre) || empty($description) || empty($ingredient) || empty($duree) || empty($difficult)) {
        $message = "Tous les champs sont obligatoires.";
    } else {
        $recette = new Recette();
        if ($recette->getById($id)) {
            $recette->set('titre', $titre);
            $recette->set('description', $description);
            $recette->set('ingredient', $ingredient);
            $recette->set('duree', $duree);
            $recette->set('difficult', $difficult);
            $recette->set('dte_maj', date('Y-m-d H:i:s'));
            if ($image) {
                $recette->set('image', $image);
            }
            $recette->update();
            $messageSuccess = "Recette mise à jour avec succès.";
        } else {
            $messageErreur = "Echec de mise à jour";
        }
    }
}

include "templates/pages/afficher_mes_recettes.php";