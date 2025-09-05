<?php
/** Template de page de création de recette
 * role : afficher le formulaire de création de recette
 */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/cree_recette.css">
    <title>Farine & Potiron - Créer une recette</title>
</head>

<body>
    <?php include "templates/fragments/Header.php"; ?>
    <h1>Crée une recette</h1>
    <?php if (!empty($message)) { ?>
    <p class="error" style="color:red; font-weight:bold;"> <?php echo htmlspecialchars($message); ?> </p>
    <?php } ?>
    <form action="cree_recette.php" method="post" enctype="multipart/form-data">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" required><br>

        <label for="chapeau">Chapeau :</label>
        <input type="text" id="chapeau" name="chapeau" required><br>

        <label for="description">Description :</label>
        <textarea id="description" cols="20" rows="10" name="description" required></textarea><br>

        <label for="farine">Farine spéciale :</label>
        <select id="farine" name="farine" required>
            <?php
            $json = file_get_contents('https://api.mywebecom.ovh/play/fep/catalogue.php');
            $farines = json_decode($json, true);
            foreach ($farines as $ref => $libelle) {
                echo '<option value="' . htmlspecialchars($ref) . '">' . htmlspecialchars($libelle) . '</option>';
            }
            ?>
        </select><br>
        <label for="ingredient">Ingrédients Utilisées</label>
        <input type="text" id="ingredient" cols="20" rows="10" name="ingredient"
            placeholder="ex: 150g sucre, 1g cannelle, 1 noix"><br>

        <label for="duree">Durée :</label>
        <input type="text" id="duree" name="duree" required><br>

        <label for="difficulte">Difficulté :</label>
        <select id="difficulte" name="difficult" required>
            <option value="facile">très facile</option>
            <option value="moyenne">facile</option>
            <option value="difficile">difficile</option>
        </select><br>

        <label for="image">Photo :</label>
        <input type="file" id="image" name="image" accept="image/*"><br>

        <button type="submit">Créer la recette</button>
    </form>
</body>

</html>