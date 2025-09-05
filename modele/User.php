<?php

Class User extends _model {
    protected $table = "User";
    protected $champs = ["id", "pseudo", "Email", "mdp", "created","image"];
    public $pseudo;


    function getByEmail($email) {
        /** Fonction pour charger un utilisateur par son email
         * Rôle : charger les données d'un utilisateur en fonction de son email
         * Paramètres : $email
         * Retour : néant
         */

        global $bdd;
        $req = $bdd->prepare("SELECT `id` FROM `User` WHERE `Email` = ?");
        $req->execute([$email]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->loadFromId($row['id']);
        } else {
            $this->id = null; // Réinitialiser l'objet si aucun utilisateur n'est trouvé
        }
    }
    function getById($id) {
        /*
        * Rôle : charger l'objet courant avec les données de la BDD
        * Paramètres : $id : identifiant de l'objet à charger
        * Retour : true si ok, false sinon
        */

        if ($id) {
            $this->loadFromId($id);
            return true;
        }
        return false;
    }
    function setMdp($mdp) {
        /*
        * Rôle : hashage du mot de passe
        * Paramètre : $mdp : le mot de passe en clair
        * Retourne le mot de passe hashé
        */
        return password_hash($mdp, PASSWORD_DEFAULT);
    }

    function getByPseudo($pseudo) {
        /** Fonction pour charger un utilisateur par son pseudo
         * Rôle : charger les données d'un utilisateur en fonction de son pseudo
         * Paramètres : $pseudo
         * Retour : néant
         */

        global $bdd;
        $req = $bdd->prepare("SELECT `id` FROM `User` WHERE `pseudo` = ?");
        $req->execute([$pseudo]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->loadFromId($row['id']);
        } else {
            $this->id = null; // Réinitialiser l'objet si aucun utilisateur n'est trouvé
        }
    }
    function newUser($pseudo, $email, $mdp) {
        return bddInsert("User", [
            "pseudo" => $pseudo,
            "Email" => $email,
            "mdp" => $this->setMdp($mdp),
            "created" => date("Y-m-d H:i:s")
        ]);
    }  
}