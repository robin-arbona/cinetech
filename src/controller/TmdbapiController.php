<?php

namespace App\controller;

use App\Model\ApiModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TmdbapiController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new ApiModel;
    }

    public function getToken(Request $request, Response $response, $args)
    {
        if ($_SESSION['connected']) {
            if ($token = $this->model->getToken($_SESSION['user']['id'])) {
                $json = ["success" => true, "message" => "Token successfully get.", "token" => $token['api_token']];
            } else {
                $json = ["success" => false, "message" => "Token were not get. Issue when query db"];
            }
        } else {
            $json = ["success" => false, "message" => "User is not connected"];
        }
        $payload = json_encode($json);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function setToken(Request $request, Response $response, $args)
    {
        if (isset($request->getParsedBody()['token'])) {
            $token = $request->getParsedBody()['token'];
        }
        if ($_SESSION['connected'] && isset($token)) {
            if ($this->model->setToken($_SESSION['user']['id'], $token)) {
                $json = ["success" => true, "message" => "Token successfully set."];
            } else {
                $json = ["success" => false, "message" => "Token were not set. Issue when updated in db"];
            }
        } else {
            $json = ["success" => false, "message" => "User is not connected or token were not given."];
        }
        $payload = json_encode($json);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getSession(Request $request, Response $response, $args)
    {
        if ($_SESSION['connected']) {
            if ($session = $this->model->getSession($_SESSION['user']['id'])) {
                $json = ["success" => true, "message" => "Session successfully get.", "session" => $session];
            } else {
                $json = ["success" => false, "message" => "Session were not get. Issue when query db"];
            }
        } else {
            $json = ["success" => false, "message" => "User is not connected"];
        }
        $payload = json_encode($json);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function setSession(Request $request, Response $response, $args)
    {
        if (isset($request->getParsedBody()['session'])) {
            $session = $request->getParsedBody()['session'];
        }
        if ($_SESSION['connected']) {
            if ($this->model->setSession($_SESSION['user']['id'], $session)) {
                $json = ["success" => true, "message" => "Session successfully set."];
            } else {
                $json = ["success" => false, "message" => "Session were not set. Issue when updated in db"];
            }
        } else {
            $json = ["success" => false, "message" => "User is not connected"];
        }
        $payload = json_encode($json);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
