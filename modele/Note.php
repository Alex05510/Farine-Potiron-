<?php 

class Note extends _model {
    protected $table = "Note";
    protected $champs = [ "user","recette"];
    protected $liens = ["user" => "User", "recette" => "Recette"];

    function getByRecette($recette_id) {
        /** Fonction pour charger les notes d'une recette par son ID
         * Rôle : charger les données des notes en fonction de l'ID de la recette
         * Paramètres : $recette_id
         * Retour : néant
         */
        $sql = "SELECT " . $this->listeChampsPourSelect() . " FROM `{$this->table}` WHERE `recette` = :recette";
        $tab = bddGetRecords($sql, [":recette" => $recette_id]);
        return $this->tab2TabObjects($tab);
    }

    function addLike($user, $recette) {
        /** Fonction pour ajouter un like à une recette
         * Rôle : ajouter un enregistrement de like dans la base de données
         * Paramètres : $user l'identifiant de l'utilisateur, $recette l'identifiant de la recette
         * Retour : néant
         */
        $sql = "INSERT INTO `{$this->table}` (`user`, `recette`) VALUES (:user, :recette)";
        bddRequest($sql, [":user" => $user, ":recette" => $recette]);
    }

    function removeLike($user, $recette) {
        /** Fonction pour supprimer un like d'une recette
         * Rôle : supprimer un enregistrement de like dans la base de données
         * Paramètres : $user_id l'identifiant de l'utilisateur, $recette_id l'identifiant de la recette
         * Retour : néant
         */
        $sql = "DELETE FROM `{$this->table}` WHERE `user` = :user AND `recette` = :recette";
        bddRequest($sql, [":user" => $user, ":recette" => $recette]);
    }
    function countLike($recette) {
        /** Fonction pour compter le nombre de likes d'une recette
         * Rôle : compter les enregistrements de likes dans la base de données
         * Paramètres : $recette l'identifiant de la recette
         * Retour : le nombre de likes
         */
        $sql = "SELECT COUNT(id) AS nb FROM `{$this->table}` WHERE `recette` = :recette";
    $row = bddGetRecord($sql, [":recette" => $recette]);
    return $row ? $row['nb'] : 0;
    }
    function isLiked($user, $recette) {
        /** Fonction pour vérifier si un utilisateur a aimé une recette
         * Rôle : vérifier l'existence d'un enregistrement de like dans la base de données
         * Paramètres : $user l'identifiant de l'utilisateur, $recette l'identifiant de la recette
         * Retour : vrai si l'utilisateur a aimé la recette, faux sinon
         */
    $sql = "SELECT COUNT(id) AS nb FROM `{$this->table}` WHERE `user` = :user AND `recette` = :recette";
    $row = bddGetRecord($sql, [":user" => $user, ":recette" => $recette]);
    return $row && $row['nb'] > 0;
}
}