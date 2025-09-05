<?php 

/** controleur de deconnexion
 * role : deconnecter l'utilisateur connecté
 */

 include "library/init.php";

 deconnecter();

    header("Location: index.php?deconnexion=success");
    exit();