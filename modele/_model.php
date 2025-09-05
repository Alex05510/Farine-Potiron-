<?php

/* Classe _model : manipulation d'un objet générique du MCD */

// La clé primaire s'appelle id
class _model {

    // Décrire l'objet réel : attributs pour décrire l'objet réel
    // On décrit le modèle conceptuel
    protected $table = "";
    protected $champs = [];     // Liste simple des champs, sauf l'id
    protected $liens = [];      // tableau  [ nomChamp => objetPointé, .... ]

    protected $valeurs = [];    // stockage des valeurs des attributs (quand remplis) [ "champ1" = "valeur1", "champ2" => "valeur2" ]
    protected $id;              // Stockage de l'id

    function __construct($id = null) {
        // Rôle : constructeur (appelée automatiquement au "new") : charger un ligne de la BDD si on précise un id
        // Paramètre :
        //      $id (facultatif) : id d la lign à charger

        if (! is_null($id)) {
            $this->loadFromId($id);
        }
    }

    function is() {
        // Rôle : indiquer si l'objt est chargé ou non
        // Paramètres : néant
        // Retour : true si l'objet est chargé, false sinon

        // Si il est chargé, on a un id on vide

        return ! empty($this->id);

    }

    function id() {
        // Rôle : retourner l'id d l'objet dans la BDD ou 0
        // Paramètres : néant
        // Retour : la valur de l'id ou 0
        return $this->id;

    }


    // Getters

    function get($nom) {
        // Rôle : getter, récupérant la valeur d'un champ donné (du modèle conceptuel, champs simples - texte, nombre, date -, liens simples)
        // Paramètres : 
        //      $nom : nom du champ à récupérer
        // Retour : valeur du champ ou valeur par défaut (chaine vide)
        //              si le champ est un lien, on retourne l'objet pointé 
        

        // $nom est-il un champ : c'est à dire si $nom existe dans le tableau $this->champs
        if ( ! in_array($nom, $this->champs))  {
            // Si ce n'est pas un champ
            return "";
        }

        // Si c'est un lien 
        if (isset($this->liens[$nom])) {
            // On veut retournr l'objt pointé
            $typeObjet = $this->liens[$nom];
            $objetPointe = new $typeObjet(isset($this->valeurs[$nom]) ? $this->valeurs[$nom] : 0);
            return $objetPointe;

        }

        // est-ce  qu'il a une valeur : il y a une valeur dans le tableau $this->valeurs
        if (isset($this->valeurs[$nom])) {
            // On a un valeur : on la retourne
            return $this->valeurs[$nom];
        } else {
            return "";
        }


    }

    // Setters

    function set($nom, $valeur) {
        // Rôle : setter, donne ou modifie la valeur d'un champ
        // Paramètres :
        //      $nom : nom du champ concerné
        //      $valeur : nouvelle valeur
        // Retour : true si ok, false sinon

        // $nom est-il un champ : c'est à dire si $nom existe dans le tableau $this->champs
        if ( ! in_array($nom, $this->champs))  {
            // Si ce n'est pas un champ : on ne peut pas mettre la valeur à jour
            return false;
        }

        // Sécurité spécifique pour le champ 'ingredient' : forcer en chaîne si objet
        if ($nom === 'ingredient' && is_object($valeur)) {
            if (method_exists($valeur, '__toString')) {
                $valeur = (string)$valeur;
            } else {
                $valeur = '';
            }
        }

        // Contrôle : empêcher d'enregistrer un tableau ou un objet comme valeur de champ
        if (is_array($valeur) || is_object($valeur)) {
            error_log("[ERREUR _model::set] Le champ '$nom' ne peut pas contenir un tableau ou un objet.");
            echo '<pre style="color:red">ERREUR: Le champ ' . htmlspecialchars($nom) . ' ne peut pas contenir un tableau ou un objet.</pre>';
            return false;
        }

        // On met la valeur dans le tableau des valeurs
        $this->valeurs[$nom] = $valeur;
        return true;

    }
    // Méthodes d'accés à la base de donnés
    //  loadFromId
    //  insert
    //  update
    //  delete
    //  listAll

    function loadFromId($id) {
        // Rôle : charger un objet depuis un ligne de la BDD ayant un id donné
        // Paramètre :
        //  $id : id à chercher
        // Retour :
        //  true si on a réussi à charger, false sinon

        // Cette méthode va donc remplir le tableau $this->valeurs

        // Construire la requête : SELECT `id`, `nomChamp1`, `nomChamp2`, ... FROM `nomTable` WHERE `id` = :id
        $sql = "SELECT " . $this->listeChampsPourSelect() . " FROM `$this->table` WHERE `id` = :id";

        // Extraire la première ligne
        $tab = bddGetRecord($sql, [ ":id" =>  $id ]);

        if ($tab == false) {
            // On n'a pas pu récupérer de ligne :
            $this->valeurs = [];
            $this->id = null;
            return false;
        }

        // On a un objet : on remplit donc les valeurs avec les données du tableau
        $this->loadFromTab($tab);
        $this->id = $id;
        return true;

    }

    function update() {
        // Rôle : mette à jour l'objet courant dans la BDD
        // Paramètres : néant
        // Retour : true si ok, false sinon

        // Si l'objet n'est pas chargé : on refuse
        if (! $this->is()) {
            return false;
        }

        return bddUpdate($this->table, $this->valeurs, $this->id);

    }

    function insert() {
        // Rôle : créer l'objet courant dans la BDD
        // Paramètres : néant
        // Retour : true si ok, false sinon

        // Si l'objet est chargé : on refuse
        if ($this->is()) {
            return false;
        }

        $id = bddInsert($this->table, $this->valeurs);
        if (empty($id)) {
            return false;
        } else {
            // mise à jour de l'id
            $this->id = $id;
            return true;
        }

    }

    function delete() {
        // Rôle : supprimer l'objet courant de  la BDD
        // Paramètres : néant
        // Retour : true si ok, false sinon

        // Si l'objet n'est pas chargé : on refuse
        if (! $this->is()) {
            return false;
        }

        $cr = bddDelete($this->table, $this->id);
        // Si cela a échoué : on retourne false
        // Sinon on vide l'id et on retourn true
        if (!$cr) {
            return false;
        } else {
            $this->id = null;
            return true;
        }

    }

    function listAll() {
        // Rôle : récupérer tous les objets de ce type dans la BDD
        // Paramètres : néant
        // Retour : list (tableau indexé) d'objete de cette nature, indexé par l'id

        // Construire la requête : SELECT `id`, `nomChamp1`, `nomChamp2`, ... FROM `nomTable` 
        $sql = "SELECT " . $this->listeChampsPourSelect() . " FROM `$this->table`";

        // Extraction des lignes :
        $tab = bddGetRecords($sql);

        // Transformation du tableau de tableau en un tableau d'objets
        return $this->tab2TabObjects($tab);

    }

    function listFiltree($filtres = [], $tri = [] ) {
        // Rôle : récupérer tous les objets de ce type dans la BDD, répondant aux critères donnés, et triés
        // Paramètres : 
        //    $filtres : tablau des valurs à chercher pour des champs donnés [ nomChamp1 => valeurCherchee, nomChamp2 => valeurCherchee, ....]
        //    $tri : tableau simple des champs de tri (tri ascendant)
        // Retour : list (tableau indexé) d'objete de cette nature, indexé par l'id

        // exemple avec la classe article, pour avoir la liste des articles d'un fournisseur donné (le 2) en stock null, triés par prix d'achat, référence
        //      $article = new article();
        //      $articles = $article->listFiltree( [ "fournisseur" => 2, "stock" => 0 ], [ "pa", "reference"]);


    }


    // Méthodes utilitaires

    function loadFromTab($tab) {  // par exempl pour un fournisseur [ "nom" => "Siemens", "Ville" => "Paris];  // les champs sont "nom", "cp", "ville", "adresse"
        // Rôle : charger les valeurs des champs à partir de données dans un tableau indexé par les noms des champs (sauf l'id)
        // Paramètres :
        //      $tab : tableau indexé comportant en index des noms de chmaps de cet objet et en valeur la valeur du champ
        // Retour : true si ok, false sinon

        // Pour chaque champ de l'objet: 
        //      Si on a une valeur dans le tableau $tab :
        //              On l'affecte au champ
        foreach ($this->champs as $nomChamp) {
            if (isset($tab[$nomChamp])) {  // Si on a une valeur dans le tableau $tab :
                // On affecte la valeur au champ : on se sert du setter : $this->set(nom du champ, valeur du champ)
                $this->set($nomChamp, $tab[$nomChamp] );
            }
        }
        return true; 

    }


    function listeChampsPourSelect() {
        // Rôle : construire la liste des champs de cet objet pour un SELECT, cad `id`,`nomChamp1`,`nomChamp2`, ...
        // Paramètres : néant
        // Retour : le texte à inclure dans du SQL

        $texte = "`id`";
        // On ajoute pour chaque champ ,`nomChamp`
        foreach($this->champs as $nomChamp) {
            $texte .= ",`$nomChamp`";
        }
        return $texte;

    }

    function tab2TabObjects($tab) {
        // Rôle : à partir d'un tableau simple de tableaux indexés des valurs des champs (type de tablau récupére la BDD) en un tablau d'objest de la classe courante
        // Paramètres :
        //      $tab : le tablau à transformer
        // Retour : tableau d'objets de la classe courant, indexé par l'id

        // On part d'un tablau de résultat vide
        $resultat = [];
        // Pour chaque élément de $tab 
        foreach ($tab as $elt) {
            // On créée un objet de la class courante
            $objet = new static();
            // On le charge
            $objet->loadFromTab($elt);
            $objet->id = $elt["id"];    // loadFromTab ne gère pas l'id : on le gère ici
            // On l'ajoute à $result au bon index
            $resultat[$objet->id()] = $objet;

        }
        return $resultat;

    }

    function search($query) {
        /** Fonction pour rechercher des objets dans la BDD
         * Rôle : effectuer une recherche dans la table de l'objet courant
         * Paramètres : $query : la chaîne de recherche
         * Retour : tableau d'objets correspondant à la recherche
         */
        $sql = "SELECT " . $this->listeChampsPourSelect() . " FROM `{$this->table}` WHERE `nom` LIKE :query";
        $tab = bddGetRecords($sql, [":query" => "%$query%"]);
        return $this->tab2TabObjects($tab);
    }

}