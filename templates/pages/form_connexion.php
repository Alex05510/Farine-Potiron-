<?php 

/** template de page de connexion
 * role : afficher le formulaire de connexion
 */
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/connexion_inscription.css">
    <link rel="stylesheet" href="css/global.css">
    <title>Farine & Potiron - Connexion</title>
</head>

<body>
    <?php include "templates/fragments/Header.php"; ?>
    <main>
    <?php if (!empty($messageSuccess)) { ?>
    <p class="success"><?php echo htmlspecialchars($messageSuccess); ?></p>
    <?php } ?>
    <?php if (!empty($message)) { ?>
    <p class="error"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>
        <form action="connexion.php" method="post">
            <h2>Connexion</h2>
            <div>
                <label for="identifiant"></label>
                <input type="text" id="identifiant" name="identifiant" placeholder="Pseudo ou Email" required>
            </div><br>
            <div>
                <label for="mdp"></label>
                <input type="mdp" id="mdp" name="mdp" placeholder="Mot de passe" required>
            </div>

            <input type="hidden" name="raison">
            <button type="submit">Se connecter</button>

            <p><a href="inscription.php">Pas encore inscrit ? Inscrivez-vous</a></p>
        </form>
    </main>
    <script src="JS/home.js"></script>
</body>

</html>