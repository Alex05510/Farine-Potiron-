  
    
<?php 

class Ingredient extends _model {
    protected $table = "Ingredient";
    protected $champs = [ "id","nom"];


    function getById($id) {
        /** Fonction pour charger un ingrédient par son ID
         * Rôle : charger les données d'un ingrédient en fonction de son ID
         * Paramètres : $id
         * Retour : néant
         */
         
        $sql = "SELECT " . $this->listeChampsPourSelect() . " FROM `{$this->table}` WHERE `id` = :id";
        $tab = bddGetRecords($sql, [":id" => $id]);
        return $this->tab2TabObjects($tab);
    }
    function all() {
    $sql = "SELECT " . implode(", ", $this->champs) . " FROM `{$this->table}`";
    $tab = bddGetRecords($sql);
    return $this->tab2TabObjects($tab);
}
function findByNom($nom) {
      /**
     * Recherche un ingrédient par son nom
     * @param string $nom
     * @return Ingredient|null
     */
        $sql = "SELECT id, nom FROM `{$this->table}` WHERE nom = :nom LIMIT 1";
        $tab = bddGetRecords($sql, [":nom" => $nom]);
        if (!empty($tab)) {
            $objets = $this->tab2TabObjects($tab);
            return $objets[0];
        }
        return null;
    }
}