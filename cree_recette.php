<?php
/** controleur de page création de recette
 * role : traiter le formulaire de création de recette
 * param : $_POST pour les données du formulaire
 */

    include "library/init.php";

$message = "";

if (!moi()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $titre = $_POST["titre"];
    $chapeau = $_POST["chapeau"];
    $description = $_POST["description"];
    $duree = intval($_POST["duree"]);
    $difficult = $_POST["difficult"];
    // Récupération des nouveaux ingrédients
    $ingredient = isset($_POST['ingredient']) ? $_POST['ingredient'] : '';
    $farine = isset($_POST['farine']) ? $_POST['farine'] : '';
    $photoPath = "";

    // Traitement de la photo
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['image']['size'] <= 2097152) {
            $photoPath = 'assets/images/' . basename($_FILES['image']['name']);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $photoPath)) {
                $message = "Erreur lors de l'enregistrement de la photo.";
            }
        } else {
            $message = "Le fichier photo est trop volumineux (max 2 Mo).";
        }
    }

    // Validation des données
    if (empty($titre) || empty($description) || empty($farine) || empty($difficult)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif ($duree <= 0) {
        $message = "La durée doit être supérieure à 0.";
    } elseif (empty($ingredient)) {
        $message = "Veuillez sélectionner au moins un ingrédient.";
    } else {
        // Ajout des nouveaux ingrédients dans la table Ingredient
        $ingredient_ids = [];
        if (!empty($ingredient)) {
            $liste_nouveaux = array_filter(array_map('trim', explode(',', $ingredient)));
            foreach ($liste_nouveaux as $nom_ingredient) {
                if (!empty($nom_ingredient)) {
                    $ingredientObj = new Ingredient();
                    // Limiter la longueur à 50 caractères
                    $nom_ingredient = mb_substr($nom_ingredient, 0, 50);
                    // Vérifier si l'ingrédient existe déjà
                    $existant = $ingredientObj->findByNom($nom_ingredient);
                    if (!$existant) {
                        // Insérer le nouvel ingrédient
                        $ingredientObj->set('nom', $nom_ingredient);
                        $ingredientObj->insert();
                        $ingredient_ids[] = $ingredientObj->id();
                    } else {
                        // Ajouter l'id existant
                        $ingredient_ids[] = $existant->id();
                    }
                }
            }
        }
        // Transformation des ingrédients en texte
        $ingredient_text = '';
        if (!empty($ingredient)) {
            $noms = [];
                if (!is_array($ingredient_ids)) {
                $ingredient_ids = [];
                }
                foreach ($ingredient_ids as $id) {
                $ingredientObj = new Ingredient();
                $tab = $ingredientObj->getById($id);
                if (!empty($tab) && isset($tab[0])) {
                    $nom = $tab[0]->get('nom');
                    if (!empty($nom)) {
                        $noms[] = $nom;
                    }
                }
            }
            $ingredient_text = implode(', ', $noms);
        }
        // Création de la recette
        $recette = new Recette();
        $data = [
            'titre' => $titre,
            'chapeau' => $chapeau,
            'description' => $description,
            'duree' => $duree,
            'difficult' => $difficult,
            'user' => $_SESSION['user_id'],
            'image' => $photoPath,
            'ingredient' => $ingredient_text,
            'farine' => $_POST['farine'],
            'dte_maj' => date('Y-m-d H:i:s')
        ];
        foreach ($data as $key => $value) {
            $recette->set($key, $value);
        }
        $recette->insert();
        $recette_id = $recette->id();
        $message = $recette_id ? "Recette créée avec succès." : "Erreur lors de la création de la recette.";
    }
}
$ingredients = new Ingredient();
$ingredients = $ingredients->all();
include "templates/pages/form_cree_recette.php";