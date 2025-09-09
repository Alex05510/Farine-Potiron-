<?php 
/** template de page profil 
 * role : afficher le profil de l'utilisateur connecté
 */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Gérez votre profil sur Farine & Potiron, modifiez vos informations personnelles et mettez à jour votre photo de profil.">
    <link rel="stylesheet" href="css/profil.css">
    <link rel="stylesheet" href="css/global.css">
    <title>Farine & Potiron - Mon profil</title>
</head>

<body>
    <?php include "templates/fragments/Header.php"; ?>
    <section class="profil">
        <?php if (!empty($messageSuccess)) { ?>
        <p class="success"><?php echo htmlspecialchars($messageSuccess); ?></p>
        <?php } ?>
        <?php if (!empty($messageSuccessImg)) { ?>
        <p class="success"><?php echo htmlspecialchars($messageSuccessImg); ?></p>
        <?php } ?>
        <h1>Mon profil</h1>
        <div class="profil">
            <div class="mon_profil">

                <form method="post">
                    <label>Pseudo : <input type="text" name="pseudo"
                            value="<?php echo htmlspecialchars($user->get('pseudo')); ?>"></label>
                    <label>Email : <input type="email" name="email"
                            value="<?php echo htmlspecialchars($user->get('Email')); ?>"></label>
                    <button type="submit" name="enregistrer">Enregistrer</button>
                </form>
            </div>
            <div class="img-profil">
                <FORM action="profil.php" method="POST" enctype="multipart/form-data">
                    <LABEL>Modifier votre photo de profil : <br> <INPUT type="FILE" name="image"
                            accept="image/*" /></LABEL>
                    <INPUT type="submit" value="Envoyer la photo" name="submit" />
                    <INPUT type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                </FORM>
                <div class="photoProfil">
                    <?php if ($user->get("image")) { ?>
                    <img src="<?php echo htmlspecialchars($user->get("image")); ?>" alt="Photo de profil" />
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</body>

</html>