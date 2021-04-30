<?php
namespace App\Model;

class InscriptionModel extends Model{
protected $pdo;
    public function createAccount($login,$email,$password){
        $model = new InscriptionModel(); // to refactorise

    

        DEFINE('DEFAULT_USER_IMG', 'https://static.wixstatic.com/media/109580_c3da31ed06484c7e8e225c46beecd507~mv2.png/v1/fill/w_220,h_220,al_c,q_85,usm_0.66_1.00_0.01/avatar%20neutre.webp');
        $image = DEFAULT_USER_IMG;
        $id_right = 1; // common user

        $sql = "INSERT INTO user (id_right, login, email, password, image) VALUES (:id_right, :login, :email , :password, :image )";
        var_dump($sql);
        $result = $this->pdo->prepare($sql);
        var_dump($result);
    
        $result->bindvalue(':id_right', $id_right, \PDO::PARAM_INT);
        $result->bindvalue(':login', $login, \PDO::PARAM_STR);
        $result->bindvalue(':email', $email, \PDO::PARAM_STR);
        $result->bindvalue(':password', $password, \PDO::PARAM_STR); // crypted
        $result->bindvalue(':image', $image, \PDO::PARAM_STR); // default img
        var_dump($result);

        $result->execute();
        var_dump($result);

    }

}


?>