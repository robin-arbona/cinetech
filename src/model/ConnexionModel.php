<?php

namespace App\Model;

class ConnexionModel extends Model{

    public function passwordVerifySql($login) 
    {
        $sql = "SELECT password FROM user WHERE login = '$login'"; // on repere le mdp crypté a comparer avec celui entré par l'utilisateur
        $result = $this->pdo->prepare($sql);
        $result->bindvalue(':login', $login, \PDO::PARAM_STR);
        $result->execute();
        $fetch = $result->fetch(\PDO::FETCH_ASSOC);
    
        return $fetch;
    }
}