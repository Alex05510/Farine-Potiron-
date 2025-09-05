<?php 
/** template de page afficher_recette
 * role afficher une recette sélectionnée par son id
 */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/recette.css">
    <link rel="stylesheet" href="css/global.css">
    <title>recette</title>
</head>

<body>
    <?php include "templates/fragments/Header.php"; ?>
    <main>

        <div class="recette">
            <div class="back">
                <a href="index.php" class="retours"><img src="assets/picto/fleche-droite.png"
                        alt="picto fleche pour retours en arriere"
                        style="transform: rotate(180deg); width: 30px; height: 30px;">retours</a>
            </div>
            <?php if ($recette): ?>
            <h1><?php echo htmlspecialchars($recette->get('titre')); ?></h1>
            <p class="chapeau"><?php echo htmlspecialchars($recette->get('chapeau')); ?></p>
            <!-- si la recette a une image on l'affiche sinon un message -->
            <?php if ($recette->get('image')): ?>
            <!-- si une recette a une image on l'affiche sinon un message pour pas d'images-->
            <img src="<?php echo htmlspecialchars($recette->get('image')); ?>"
                alt="image de la recette proposée (<?php echo htmlspecialchars($recette->get('titre')); ?>)"
                width="300px" height="auto">
            <?php else: ?>
            <p>Pas d'image pour cette recette</p>
            <?php endif; ?>
            <h2>Description</h2>
            <p><?php echo htmlspecialchars($recette->get('description')); ?></p>
            <div class="userPost">
                <!-- on affiche le pseudo de l'auteur, la durée et la difficulté de la recette-->
                <p>Par : <?php echo htmlspecialchars($recette->getAuteurPseudo()); ?></p>
                <p>Durée : <?php echo htmlspecialchars($recette->get('duree')); ?>/min</p>
                <p>Difficulté : <?php echo htmlspecialchars($recette->get('difficult')); ?></p>
            </div>
            <div class="ingredients">
                <h2 class="ingr">Ingrédients</h2>
                <ul>
                    <?php 
            $ingredients = explode(',', $recette->get('ingredient'));
            foreach ($ingredients as $ingredient): ?>
                    <li><?php echo htmlspecialchars(trim($ingredient)); ?></li>
                    <?php endforeach; ?>
            </div>
            </ul>
            <div class="farine_utiliser">
                <h3>Farine utilisée pour cette recette</h3>
                <?php foreach ($farines_info as $info): ?>
                <!-- on boucle sur les farines utilisées et on affiche le nom et la description-->
                <p><strong><?php echo htmlspecialchars($info['nom']);?></strong></p>
                <p><?php echo nl2br(htmlspecialchars($info['description'])); ?></p>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <?php if (isset($user_id) && $user_id): ?>
            <?php if ($note->isLiked($user_id, $recette_id)): ?>
            <!-- on recupere l'id de la recette dans $recette_id pour la gestion des likes dislikes -->
            <a href="like_recette.php?id=<?php echo $recette_id; ?>&unlike=1" class="dislike">Je n’aime plus</a>
            <?php else: ?>
            <a href="like_recette.php?id=<?php echo $recette_id; ?>&like=1" class="like">J’aime</a>
            <?php endif; ?>
            <span><?php echo $note->countLike($recette_id); ?> likes</span>
            <?php 
        $users_likes = $note->getByRecette($recette_id);
        foreach ($users_likes as $like) {
            $user_like = $like->get('user');
            if ($user_like && $user_like->get('image')) {
                echo '<img src="' . htmlspecialchars($user_like->get('image')) . '" alt="photo de profil" style="width:40px; height:40px; border-radius:50%; margin-right:4px;">';
            }
        }
        ?>
            <?php endif; ?>
        </div>

        <div class="commentaire">
            <h2>Commentaires</h2>
            <ul>
                <?php if (count($commentaires) == 0): ?>
                <p>Aucun commentaire pour le moment, et si vous étiez le premier&nbsp;?</p>
                <?php else: ?>
                <?php foreach ($commentaires as $commentaire): ?>
                <?php $user = $commentaire->get('user'); ?>
                <li>
                    <div class="img-profilRecette">
                        <img src="<?php echo htmlspecialchars($user->get('image')); ?>" alt="photo de profil"
                            style="width:80px; height:80px; border-radius:50%;">
                    </div>
                    <div class="contenu">
                        <!-- on affiche le pseudo de l'auteur du commentaire, le contenu et la date-->
                        <strong><?php echo htmlspecialchars($commentaire->getAuteurPseudo()); ?></strong> :
                        <?php echo htmlspecialchars($commentaire->get('contenu')); ?>
                        <br><span class="date">mis à jour le:
                            <?php echo date('d/m/Y', strtotime($commentaire->get('date'))); ?></span><br>
                        <?php if ($user == moi()): ?>
                        <a href="modifier_commentaire.php?id=<?php echo $commentaire->id(); ?>"
                            class="modifier-commentaire">modifier le
                            commentaire</a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <?php if ($user = moi()): ?>
            <!-- si l'utilisateur est connecté on affiche le formulaire pour ajouter un commentaire-->
            <form method="post" action="ajouter_commentaire.php">
                <input type="hidden" name="recette_id" value="<?php echo $recette->get('id'); ?>">
                <textarea name="contenu" rows="4" cols="50" placeholder="Ajouter un commentaire..."></textarea>
                <button type="submit">Envoyer</button>
            </form>
            <?php else: ?>
            <p><a href="connexion.php">connectez vous</a> pour laisser un commentaire</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>