<?php

namespace App\controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends Controller
{
    public function main(Request $request, Response $response, $args)
    {
        $this->preloadTwig();
        $response->getBody()->write($this->twig->render('home.twig.php'));
        return $response;
    }
    public function getInscription(Request $request, Response $response, $args)
    {

        $method = $request->getMethod();
        if ($method == 'POST') {

            $params = (array)$request->getParsedBody();
            $login = $params['createLogin'];
            $email = $params['createEmail'];
            $password = $params['createPassword'];
            $confirm_password = $params['confirmCreatePassword'];
            var_dump($params);
            $InscriptionController = new \App\controller\InscriptionController();
            $InscriptionController->register($login, $email, $password, $confirm_password);
        } else {
            $login = "";
            $email = "";
            $password = "";
            $confirm_password = "";
        }

        $this->preloadTwig();
        $response->getBody()->write($this->twig->render(
            'inscription.twig.php',
            [
                'BASE_PATH' => BASE_PATH, "HTTP_HOST" => HTTP_HOST, 'method' => $method,
                'login' => $login, 'email' => $email, 'password' => $password, 'confirm_password' => $confirm_password
            ]
        ));



        return $response;
    }
    public function getConnexion(Request $request, Response $response, $args)
    {
        $method = $request->getMethod();
        if ($method == 'POST') {

            $params = (array)$request->getParsedBody();

            $login = $params['logLogin'];
            $password = $params['logPassword'];

            $InscriptionController = new \App\controller\ConnexionController();
            $InscriptionController->connect($login, $password);
        } else {
            $login = "";
            $email = "";
            $password = "";
            $confirm_password = "";
        }

        $this->preloadTwig();
        $response->getBody()->write($this->twig->render(
            'connexion.twig.php',
            ['BASE_PATH' => BASE_PATH, 'method' => $method, 'login' => $login, 'password' => $password, "HTTP_HOST" => HTTP_HOST]
        ));



        return $response;
    }
    public function logOut(Request $request, Response $response, $args)
    {
        session_destroy();
        return $response
            ->withHeader('Location', 'http://' . HTTP_HOST . BASE_PATH)
            ->withStatus(302);
    }
    public function getProfil(Request $request, Response $response, $args)
    {
        $method = $request->getMethod();
        if ($method == 'POST') {

            $params = (array)$request->getParsedBody();

            $image = $params['profilImage'];
            $login = $params['profilLogin'];
            $email = $params['profilEmail'];
            $password = $params['profilPassword'];
            $confirm_password = $params['confirmProfilPassword'];


            $ProfilController = new \App\controller\ProfilController();
            $ProfilController->modifyProfil($login, $password, $confirm_password, $email, $image);
        } else {
            $image = "";
            $login = "";
            $email = "";
            $password = "";
            $confirm_password = "";
        }

        $this->preloadTwig();
        $response->getBody()->write($this->twig->render(
            'profil.twig.php',
            [
                'BASE_PATH' => BASE_PATH, "HTTP_HOST" => HTTP_HOST, 'method' => $method, 'login' => $login, 'password' => $password,
                'email' => $email, 'image' => $image
            ]
        ));



        return $response;
    }
}
