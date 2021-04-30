<?php

namespace App\Model;

class ApiModel extends Model
{
    public function setToken($id, $token)
    {

        $sql = "UPDATE `user` SET `api_token`=:token WHERE id=:id";
        $result = $this->pdo->prepare($sql);
        $result->bindValue(':id', $id, \PDO::PARAM_INT);
        $result->bindValue(':token', $token, \PDO::PARAM_STR);
        return $result->execute();
    }

    public function getToken($id)
    {
        $sql = "SELECT api_token FROM user WHERE id = :id";
        $result = $this->pdo->prepare($sql);
        $result->bindValue(':id', $id, \PDO::PARAM_INT);
        $result->execute();
        $fetch = $result->fetch();
        return $fetch;
    }

    public function setSession($id, $session)
    {

        $sql = "UPDATE `user` SET `api_session`=:session WHERE id=:id";
        $result = $this->pdo->prepare($sql);
        $result->bindValue(':id', $id, \PDO::PARAM_INT);
        $result->bindValue(':session', $session, \PDO::PARAM_STR);
        return $result->execute();
    }

    public function getSession($id)
    {
        $sql = "SELECT api_session FROM user WHERE id = :id";
        $result = $this->pdo->prepare($sql);
        $result->bindValue(':id', $id, \PDO::PARAM_INT);
        $result->execute();
        $fetch = $result->fetch();
        return $fetch;
    }
}
