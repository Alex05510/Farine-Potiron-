<?php
    /** controleur principal 
     * role : préparer le template de la homePage
     */

    include  "library/init.php";
    $user = moi();
    $recettes = new Recette();
    $recettes = $recettes->getDernieresRecettes();
    include "templates/pages/Home.php";