<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

namespace App\controller;


class ReviewController extends Controller
{

    public function get($request, $response, $args)
    {
        $ReviewModel = new \App\Model\ReviewModel();
        $result = $ReviewModel->fetchReview_id_type($args['id'], $args['type']);


        $result = json_encode($result);

        $response->getBody()->write($result); // write json encodé

        return $response->withHeader('Content-Type', 'application/json');
    }


    public function add($request, $response, $args)
    {

        $controllerReview = new \App\controller\ReviewController();
        $modelReview = new \App\Model\ReviewModel(); // on pourrait appeler n'importe laquelle
        $id_user = $controllerReview->secure($_SESSION['user']['id']);
        $commentary = $controllerReview->secure($_POST['commentary']); // args['qqchose']
        $id_comment = $controllerReview->secure($_POST['replyComment']);
        $id_program = $controllerReview->secure($args['id']);
        $type_program = $controllerReview->secure($args['type']);

        $errorLog = "";

        if (!empty($id_user) && !empty($commentary) && !empty($id_program) && !empty($type_program)) {
            $commentary_len = strlen($commentary);
            if ($commentary_len < 1000) {
                if ($id_comment !== "") {
                    $modelReview->insertReply($id_user, $commentary, $id_program, $type_program, $id_comment);
                } else {
                    $modelReview->insertComment($id_user, $commentary, $id_program, $type_program);
                }
            } else $errorLog = "La limite de caractere est fixée a 1000";
        } else $errorLog = "Veuillez entrer des caracteres dans les champs";

        if ($errorLog == "") {
            $response->getBody()->write(json_encode(["success" => true, "msg" => "Comment added"])); // write json encodé
            $response->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(["success" => false, "msg" => $errorLog])); // write json encodé
            $response->withStatus(500);
        }


        return $response->withHeader('Content-Type', 'application/json');;
    }
}
