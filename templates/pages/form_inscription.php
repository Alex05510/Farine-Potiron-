<?php 
/** template de la page d'inscription
 * role : afficher le formulaire d'inscription
 */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/connexion_inscription.css">
    <link rel="stylesheet" href="css/global.css">
    <title>Farine & Potiron - Inscription</title>
</head>

<body>
    <?php include "templates/fragments/Header.php"; ?>
    <main>

        <?php if (!empty($messageError)) { ?>
        <p class="error"><?php echo htmlspecialchars($messageError); ?></p>
        <?php } ?>
        <?php if (!empty($messageSuccess)) { ?>
        <p class="success"><?php echo htmlspecialchars($messageSuccess); ?></p>
        <?php } ?>
        <form action="inscription.php" method="post">
            <h2>Inscription</h2>
            <div class="pseudo">
                <label for="pseudo"></label>
                <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo" required>
            </div><br>
            <div class="email">
                <label for="email"></label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div><br>
            <div class="mdp">
                <label for="mdp"></label>
                <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required>
            </div><br>
            <button type="submit">S'inscrire</button>

            <p><a href="connexion.php">Déjà inscrit ? Connectez-vous</a></p>
        </form>
    </main>
</body>

</html>