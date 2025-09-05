<?php 
/** fragment de header */
?>
<header>
    <div class="logo">
        <a href="index.php">
            <img src="assets/logo/logo.png" alt="logo du site Farine & Potiron" width="100px" height="auto">
            <h1>Farine & Potiron</h1>
        </a>
    </div>
    <nav>
        <ul>

            <?php $user = moi(); 
            if ($user) { ?>

            <?php if ($user->get("image")) { ?>
            <li><img src="<?php echo htmlspecialchars($user->get("image")); ?>" alt="Photo de profil"
                    style="width: 50px; height:50px;border-radius: 50%;object-fit: cover;"></li>
            <?php }else { ?>
            <li><img src="assets/picto/utilisateur.png" alt="Photo de profil par défaut"
                    style="width: 50px; height:50px;"></li>
            <?php } ?>
            <li><a href="profil.php" class="monCompte">Mon Compte</a></li>
            <li><a href="deconnexion.php" class="deconnexion">Déconnexion</a></li>
            <?php } else { ?>
            <li><img src="assets/picto/utilisateur.png" alt="Photo de profil par défaut"
                    style="width: 50px; height:50px;"></li>
            <li><a href="connexion.php" class="connexion">connexion</a></li>

            <li><a href="inscription.php" class="inscription">inscription</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>