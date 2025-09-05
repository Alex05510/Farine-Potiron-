<?php 

class Commentaire extends _model {
    
    protected $table = "Commentaire";
    protected $champs = [ "user","recette", "contenu", "date"];
    protected $liens = ["user" => "User", "recette" => "Recette"];

    function getByRecette($recette_id) {
        /** Fonction pour charger les commentaires d'une recette par son ID
         * Rôle : charger les données des commentaires en fonction de l'ID de la recette
         * Paramètres : $recette_id
         * Retour : tableau d'objets Commentaire
         */
        $sql = "SELECT " . $this->listeChampsPourSelect() . " FROM `{$this->table}` WHERE `recette` = :recette";
        $tab = bddGetRecords($sql, [":recette" => $recette_id]);
        return $this->tab2TabObjects($tab);
}
     function save() {
        if ($this->is()) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }
     function getAuteurPseudo() {
    $user = $this->get('user');
    if ($user instanceof User) {
        return $user->get('pseudo');
    }
    return '';
}
    function updateContenu($contenu) {
        /** fonction update commentaire
         * role : mettre à jour le contenu d'un commentaire
         * parametre : $contenu le nouveau contenu du commentaire
         */
    $sql = "UPDATE `{$this->table}` SET `contenu` = :contenu WHERE `id` = :id";
    bddRequest($sql, [":contenu" => $contenu, ":id" => $this->id()]);
    $this->valeurs['contenu'] = $contenu;
    }
}