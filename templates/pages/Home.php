<?php 
    /** template de la page d'accueil 
     * role : afficher les informations de la page d'accueil
    */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/modale.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/home.css">
    <title>Farine & Potiron - Accueil</title>
</head>

<body>
    <?php include "templates/fragments/Header.php"; ?>
    <main>

        <div class="perso">
            <?php if ($user = moi()) : ?>
            <ul>
                <li><a href="mes_recettes.php">Mes recettes</a></li>
                <li><a href="cree_recette.php">Crée une recette</a></li>
            </ul>
            <?php endif; ?>
            <button id="voir-farines">Nos Farines</button>
            <div id="modal-overlay"></div>
            <div id="modal">
                <span id="modal-close" style="cursor:pointer; float:right;">&times;</span>
                <div id="modal-content"></div>
            </div>
            <form class="search" method="get" action="recherche.php">
                <input type="text" name="search" placeholder="Rechercher une recette...">
                <button type="submit">Rechercher</button>
            </form>
        </div>

        <div class="derRecettes">
            <h2>Les dernières recettes</h2>
            <div class="aLaUne">
                <?php foreach ($recettes as $recette) : ?>
                <div class="recette">
                    <h3><?php echo htmlspecialchars($recette->get("titre")); ?></h3>
                    <p><?php echo htmlspecialchars($recette->get("chapeau")); ?></p>
                    <div class="infos">
                        <span>Posté par : <?php echo htmlspecialchars($recette->getAuteurPseudo()); ?></span>
                        <?php if ($recette->get('dte_maj')): ?>
                        <span class="maj">Dernière mise à jour :
                            <?php echo date('d/m/Y H:i', strtotime($recette->get('dte_maj'))); ?></span>
                        <?php endif; ?>
                        <button><a href="recette.php?id=<?php echo $recette->get('id'); ?>">Voir la recette <img
                                    src="assets/picto/fleche-droite.png" alt=""
                                    style="width: 30px; height: auto;"></a></button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <script src="JS/home.js"></script>
</body>

</html>