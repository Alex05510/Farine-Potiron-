<?php
/** template de la page de résultats de recherche
 * role : afficher les résultats de la recherche de recettes
 * paramètres : $mot (le mot-clé recherché), $recettes (les recettes trouvées)
 */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Résultats de la recherche</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <?php include "templates/fragments/Header.php"; ?>
    <main>
        <h2>Résultats pour "<?php echo htmlspecialchars($mot); ?>"</h2>
        <div class="aLaUne recherche">
            <?php if (!empty($recettes)): ?>
            <?php foreach ($recettes as $recette): ?>
            <div class="recette">
                <h3><?php echo htmlspecialchars($recette['titre']); ?></h3>
                <span><img src=" <?php echo htmlspecialchars($recette['image']); ?>" alt=""></span>
                <p><?php echo htmlspecialchars($recette['description']); ?></p>

                <div class="infos">
                    <a href="recette.php?id=<?php echo htmlspecialchars($recette['id']); ?>" class="voir-recette">
                        Voir la recette <img src="assets/picto/fleche-droite.png" alt=""
                            style="width: 30px; height: auto;">
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p>Aucune recette trouvée.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>