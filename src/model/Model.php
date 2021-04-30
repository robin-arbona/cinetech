<?php
namespace App\Model;

abstract class  Model {

    protected $pdo = 'NULL';

    public function __construct(){
        $pdo = new \PDO('mysql:host=localhost;dbname=cinetech;charset=utf8', 'root', '' );

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $this->pdo = $pdo;

        return $pdo;
    }
    public function alreadyTakenCheck($nomTable, $colonne, $value) // Est ce que l'utilisateur existe ? 
    {                              // si oui alors on need un new pseudo
        $sql = "SELECT $colonne FROM $nomTable WHERE $colonne = ?";
        $result = $this->pdo->prepare($sql);
        $result->execute([$value]);
        $fetch = $result->fetch(\PDO::FETCH_ASSOC);
        return $fetch;
    }
    public function selectAllWhere($nomTable,$colonne,$value)
    { // select * where value = value
        $sql = "SELECT * FROM $nomTable WHERE $colonne= ?";
        $result = $this->pdo->prepare($sql);
        $result->execute([$value]);
        $fetch = $result->fetch(\PDO::FETCH_ASSOC);

        return $fetch;
    }
    public function checkOneValue($column, $table, $login) // si l'utilisateur n'existe pas déjà return true 
        {
            $sql = "SELECT $column FROM $table WHERE login = :login";
            $result = $this->pdo->prepare($sql);
            $result->bindvalue(':login', $login, \PDO::PARAM_STR);
            $result->execute();
            $fetch = $result->fetch(\PDO::FETCH_ASSOC);
    
            if ($fetch) {
                return true;
            } else {
                echo "Ce compte n'existe pas";
                return false;
            }
        }
    public function updateOneValue($nomTable, $colonne1,$colonne2, $value1,$value2){
    
        $sql = "UPDATE $nomTable SET $colonne1 = :value1 WHERE $colonne2= :value2";
        $result = $this->pdo->prepare($sql);
        $result->bindValue(":value1",$value1,\PDO::PARAM_STR);  
        $result->bindValue(":value2",$value2,\PDO::PARAM_INT);  
        $result->execute();
    }

}


?>