<?php 

class Recette extends _model {
    protected $table = "Recette";
    protected $champs = [ "id","user","titre","chapeau","description","farine", "ingredient","dte_maj", "duree", "difficult","image"];
    protected $liens = ["user_id" => "User"];
    // Pour l'affichage, on utilise une propriété différente
    public $ingredientsObjets = [];


    function getById($id) {
    $sql = "SELECT r.`user`, r.`titre`, r.`description`,r.`chapeau`, r.`ingredient`,r.`dte_maj`,r.`farine`, r.`duree`, r.`difficult`,r.`image`, i.nom AS ingredient FROM Recette r LEFT JOIN Ingredient i ON r.ingredient = i.id WHERE r.id = :id";
    $row = bddGetRecord($sql, [":id" => $id]);

    if ($row) {
        $this->valeurs = $row;
        $this->id = $id;

        // On stocke le nom complet de l'ingrédient dans un tableau pour compatibilité avec la vue
        $this->ingredientsObjets = [];
        if (!empty($row['ingredient'])) {
            // On crée un objet Ingredient minimal avec le nom complet
            $ingredientObj = new Ingredient();
            $ingredientObj->set('nom', $row['ingredient']);
            $this->ingredientsObjets[] = $ingredientObj;
        }
        return true;
    }
    return false;
}

     function listByUser($userId) {
        /** Fonction pour lister les recettes d'un utilisateur par son ID
         * Rôle : charger les données des recettes en fonction de l'ID de l'utilisateur
         * Paramètres : $userId
         * Retour : tableau d'objets Recette
         */
        $sql = "SELECT " . $this->listeChampsPourSelect() . " FROM `{$this->table}` WHERE `user` = :user";
        $tab = bddGetRecords($sql, [":user" => $userId]);
        return $this->tab2TabObjects($tab);
    }

    
    function getAuteurPseudo() {
        /** fonction pour obtenir le pseudo de l'auteur de la recette
         * role : récupérer le pseudo de l'utilisateur associé à la recette
         * paramètres : néant
         */
        $userId = $this->get('user');
        $user = new User();
        $user->getById($userId);
        return $user->get("pseudo");
    }
    function getDernieresRecettes() {
        /** Fonction pour obtenir les dernières recettes
         * Rôle : récupérer les dernières recettes ajoutées
         * Paramètres : $limit (nombre de recettes à récupérer, par défaut 4)
         * Retour : tableau d'objets Recette
         */
        $sql = "SELECT " . $this->listeChampsPourSelect() . " FROM `{$this->table}` ORDER BY `id`";
        $tab = bddGetRecords($sql);
        return $this->tab2TabObjects($tab);
    }
    function insert() {
        global $bdd;
        $sql = "INSERT INTO `{$this->table}` (`user`, `titre`,`chapeau`, `description`, `ingredient`,`dte_maj`, `farine`, `duree`, `difficult`, `image`) 
                VALUES (:user, :titre, :chapeau, :description, :ingredient, :dte_maj, :farine, :duree, :difficult, :image)";
        $req = $bdd->prepare($sql);
        $req->bindValue(':user', $this->get('user'));
        $req->bindValue(':titre', $this->get('titre'));
        $req->bindValue(':chapeau', $this->get('chapeau'));
        $req->bindValue(':description', $this->get('description'));
        $req->bindValue(':ingredient', $this->get('ingredient'));
        $req->bindValue(':dte_maj', date('Y-m-d H:i:s'));
        $req->bindValue(':farine', $this->get('farine'));
        $req->bindValue(':duree', $this->get('duree'));
        $req->bindValue(':difficult', $this->get('difficult'));
        $req->bindValue(':image', $this->get('image'));
        $success = $req->execute();
        if ($success) {
            $this->set('id', $bdd->lastInsertId());
            return $bdd->lastInsertId();
        }
        return false;
    }
    function search($mot) {
        /** Fonction pour rechercher des recettes par mot-clé
         * Rôle : effectuer une recherche dans les titres, descriptions et ingrédients
         * Paramètres : $mot (le mot-clé à rechercher)
         * Retour : tableau d'objets Recette correspondant à la recherche
         */
    global $bdd;
    $sql = "SELECT `id`,`user`, `titre`, `chapeau`, `description`,`dte_maj`, `farine`, `ingredient`, `duree`, `difficult`, `image` FROM Recette WHERE titre LIKE :mot OR description LIKE :mot OR ingredient LIKE :mot";
    $req = $bdd->prepare($sql);
    $req->execute(['mot' => "%$mot%"]);
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
}