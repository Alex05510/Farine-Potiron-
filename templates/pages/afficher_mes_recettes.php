<?php 

/** template de page qui affiche mes recettes
 * role : afficher les recettes de l'utilisateur connecté
 */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/mes_recettes.css">
    <link rel="stylesheet" href="css/global.css">
    <title>Mes recettes</title>
</head>

<body>
    <?php include "templates/fragments/Header.php"; ?>
    <main>
        <section class="mes_recettes">
            <h2>Mes recettes</h2>
            <?php if (!empty($message)) { ?>
            <p class="succes"><?php echo htmlspecialchars($message); ?></p>
            <?php } ?>
            <ul>
                <?php foreach ($recettes as $recette): ?>
                <form method="post" action="modifier_recette.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $recette->get('id'); ?>">
                    <li>Titre :<br /> <input type="text" name="titre"
                            value="<?php echo htmlspecialchars($recette->get('titre')); ?>">
                    </li>
                    <li>Chapeau :<br /> <input type="text" name="chapeau"
                            value="<?php echo htmlspecialchars($recette->get('chapeau')); ?>">
                    </li>
                    <li>Description :<br /> <textarea name="description" rows="10"
                            cols="20"><?php echo htmlspecialchars($recette->get('description')); ?></textarea></li>
                    <li>Ingrédients :<br /> <textarea name="ingredient" cols="20"
                            rows="10"><?php echo htmlspecialchars($recette->get('ingredient')); ?></textarea></li>
                    <li>Durée :<br /> <input type="text" name="duree"
                            value="<?php echo htmlspecialchars($recette->get('duree')); ?>">
                    </li>
                    <li>
                        <select name="difficult">
                            <option value="facile" <?php if($recette->get('difficult')=='facile') echo 'selected'; ?>>
                                très
                                facile</option>
                            <option value="moyenne" <?php if($recette->get('difficult')=='moyenne') echo 'selected'; ?>>
                                facile</option>
                            <option value="difficile"
                                <?php if($recette->get('difficult')=='difficile') echo 'selected'; ?>>
                                difficile</option>
                        </select>
                    </li>
                    <li>
                        <label>Image :</label>
                        <input type="file" name="image" accept="image/*">
                        <?php if ($recette->get('image')): ?>
                        <img src="<?php echo htmlspecialchars($recette->get('image')); ?>" alt="image recette"
                            width="80">
                        <?php endif; ?>
                    </li>
                    <button type="submit">Enregistrer</button>
                </form>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>

</html>