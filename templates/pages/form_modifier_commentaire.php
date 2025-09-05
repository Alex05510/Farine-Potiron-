<?php
/** Formulaire de modification de commentaire */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <title>Modifier commentaire</title>
</head>

<body>
    <?php include "templates/fragments/Header.php"; ?>
    <main>
        <h2>Modifier votre commentaire</h2>
        <?php if (!empty($message)) { ?>
        <p><?php echo htmlspecialchars($message); ?></p>
        <?php } ?>
        <?php if ($commentaire): ?>
        <form method="post">
            <textarea name="contenu" rows="4"
                cols="50"><?php echo htmlspecialchars($commentaire->get('contenu')); ?></textarea><br>
            <button type="submit">Enregistrer</button>
        </form>
        <?php endif; ?>
        <p><a href="recette.php?id=<?php echo htmlspecialchars($commentaire->get('recette')->id()); ?>">Retour</a></p>
    </main>
</body>

</html>