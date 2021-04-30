<?php

namespace App\Model;

class ReviewModel extends Model
{
    public function fetchReview_id_type($id, $type)
    {

        $sql = "SELECT m.id as id_commentaire, u.login as author, m.date as created_at , u.image , m.commentary as content, m.id_program , m.type_program FROM user AS u 
        INNER JOIN movie_commentary AS m ON u.id = m.id_user WHERE m.type_program = :type AND id_program= :id";
        $result = $this->pdo->prepare($sql);
        $result->bindValue(':id',$id,\PDO::PARAM_INT);
        $result->bindValue(':type',$type,\PDO::PARAM_STR);
        $result->execute();

        $fetch = $result->fetchAll();
        
        $ReviewModel = new \App\Model\ReviewModel();
        $tab = array();
        $i = 0;
        foreach($fetch as $value){
            array_push($tab, $fetch[$i]);
            $fetch2 = $ReviewModel->fetchReview_id_review_reply($fetch[$i]['id_program'],$fetch[$i]['type_program'],$fetch[$i]['id_commentaire']);
            for($e = 0 ; $e < count($fetch2); $e++){
                array_push($tab, $fetch2[$e]);
            }                
                $i++;
            }

        return $tab;

    }
    public function fetchReview_id_review_reply($id,$type,$id_commentary){

        $sql = "SELECT u.login as author, m.date as created_at , u.image , m.reply as content FROM user AS u 
        INNER JOIN movie_commentary_reply AS m ON u.id= id_user WHERE m.type_program = :type AND id_program = :id AND id_commentary = :id_commentary ";

        $result = $this->pdo->prepare($sql);
        $result->bindValue(':id',$id,\PDO::PARAM_INT);
        $result->bindValue(':type',$type,\PDO::PARAM_STR);
        $result->bindValue(':id_commentary',$id_commentary,\PDO::PARAM_STR);
        $result->execute();

        $fetch = $result->fetchAll();

        return $fetch;
    }
    public function insertReply($id_user, $reply, $id_program, $type_program, $id_commentary){
        $temps = time();
        $date = date('Y-m-d H:i', $temps);
        
        $sql = "INSERT INTO movie_commentary_reply (id_user, id_commentary, reply, id_program, type_program , date) 
        VALUES (:id_user, :id_commentary, :reply, :id_program, :type_program, :date)";
        $result = $this->pdo->prepare($sql);
        $result->bindValue(':id_user',$id_user,\PDO::PARAM_INT);
        $result->bindValue(':id_commentary',$id_commentary,\PDO::PARAM_INT);
        $result->bindValue(':reply',$reply,\PDO::PARAM_STR);
        $result->bindValue(':id_program',$id_program,\PDO::PARAM_INT);
        $result->bindValue(':type_program',$type_program,\PDO::PARAM_STR);
        $result->bindValue(':date',$date,\PDO::PARAM_STR);

        $result->execute();
    }
    public function insertComment($id_user, $commentary, $id_program, $type_program){

        $temps = time();
        $date = date('Y-m-d H:i', $temps);

        $sql = "INSERT INTO movie_commentary (id_user, commentary, id_program, type_program , date) VALUES (:id_user, :commentary, :id_program, :type_program, :date)";
        $result = $this->pdo->prepare($sql);
        $result->bindValue(':id_user',$id_user,\PDO::PARAM_INT);
        $result->bindValue(':commentary',$commentary,\PDO::PARAM_STR);
        $result->bindValue(':id_program',$id_program,\PDO::PARAM_INT);
        $result->bindValue(':type_program',$type_program,\PDO::PARAM_STR);
        $result->bindValue(':date',$date,\PDO::PARAM_STR);

        $result->execute();
    }
}
