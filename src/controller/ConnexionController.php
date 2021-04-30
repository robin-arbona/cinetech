<?php

namespace App\controller;

use App\Model\ApiModel;

class ConnexionController extends Controller
{
    public function connect($login, $password)
    {
        $login = $login;
        $password = $password;
        $errorLog = null;

        if (!empty($login) && !empty($password)) { // il faut remplir les champs sinon $errorLog

            $ControllerConnexion = new \App\Controller\ConnexionController();
            $modelConnexion = new \App\Model\ConnexionModel();
            
            $login = $ControllerConnexion->secure($login);
            $password = $ControllerConnexion->secure($password);

            $fetch = $modelConnexion->checkOneValue('login', 'user', $login); // savoir si le compte existe pour etre connecté
            if ($fetch) {
                $passwordSql = $modelConnexion->passwordVerifySql($login);

                
                if (password_verify($password, $passwordSql['password'])) {
                    $_SESSION['connected'] = true;
                    $utilisateur = $modelConnexion->selectAllWhere('user','login',$login);
                    $_SESSION['user'] = $utilisateur; // la carte d'identité de l'utilisateur à été créer et initialisé dans une $_SESSION
                    
                    // $this->id = $utilisateur['user']['id'];
                    // $this->login = $utilisateur['user']['login'];
                    // $this->email = $utilisateur['user']['email'];
                    // $this->id_right = $utilisateur['user']['id_right'];
                    
                    // Delete session id & user
                    $apiModel = new ApiModel();
                    $apiModel->setSession($_SESSION['user']['id'],"");
                    $apiModel->setToken($_SESSION['user']['id'],"");

                    $this->redirect('profil'); // GG WP
                } else {
                    $errorLog = "<p class='alert alert-danger' role='alert'>Mot de passe incorrect</p>";
                }
            } else {
                $errorLog = "<p class='alert alert-danger' role='alert'>Identifiant incorrect</p>";
            }
        } else {
            $errorLog = "<p class='alert alert-danger' role='alert'>Veuillez entrer des caracteres dans les champs</p>";
        }
        echo $errorLog; // on aurait pu mettre un return mais flemme :-) pour un prochain projet
    }

}
