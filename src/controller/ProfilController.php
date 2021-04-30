<?php

namespace App\controller;

class ProfilController extends Controller{

    public function modifyProfil($login, $password, $confirm_password, $email, $image)
    {
        $controllerProfil = new \App\Controller\ProfilController();

        var_dump(strlen('https://images-na.ssl-images-amazon.com/images/I/51VNG7R87NL._AC_SY445_.jpg'));

        $login = $controllerProfil->secure($login);
        $password = $controllerProfil->secure($password);        //securisé    
        $email = $controllerProfil->secure($email);
        $confirm_password = $controllerProfil->secure($confirm_password);                 
        $image = $controllerProfil->secure($image);

        $errorLog = null;

        $login_len = strlen($login);
        $password_len = strlen($password);
        $confirm_password_len = strlen($confirm_password);
        $email_len = strlen($email);
        $image_len = strlen($image);

        $modelProfil = new \App\Model\ProfilModel();

            if(!empty($login)){
                if($login_len >= 2){
                    if($login_len <= 30){

                        $new_name = $modelProfil->alreadyTakenCheck('user','login',$login);
                        
                        if (!$new_name) {

                            $modelProfil->updateOneValue('user', 'login','id', $login, $_SESSION['user']['id']);
                            
                            $fetch_utilisateur = $modelProfil->selectAllWhere('user','id',$_SESSION['user']['id']); // je trouve mon id en dehors des session 
                            $_SESSION['user'] = $fetch_utilisateur;
                            echo "changement(s) effectué(s) login";

                        }
                    }
                }
            }

            if((!empty($password)) && (!empty($confirm_password)))
            {
                if(($confirm_password_len >= 4) && ($password_len  >= 5))
                {
                    if(($confirm_password_len <= 30) && ($password_len <= 30))
                    {
                        if ($password == $confirm_password) 
                        {
                            $cryptedpassword = password_hash($password, PASSWORD_BCRYPT);

                            $modelProfil->updateOneValue('user', 'password','id', $cryptedpassword, $_SESSION['user']['id']);

                            $fetch_utilisateur = $modelProfil->selectAllWhere('user','id',$_SESSION['user']['id']); // je trouve mon id en dehors des session 
                            $_SESSION['user'] = $fetch_utilisateur;
                            echo "changement(s) effectué(s) password";
                        }         
                        else {
                            $errorLog = "<p>Confirmation du mot de passe incorrect</p>";
                        }
                    }
                }
            }

            if(!empty($email)){

                if($email_len>=7){
    
                    if($email_len<=30){
                    $fetch_utilisateur = $modelProfil->selectAllWhere('user','login',$login); // je trouve mon id en dehors des session 

                    $new_email = $modelProfil->alreadyTakenCheck('user','email',$email);

                    if (!$new_email) {

                            $modelProfil->updateOneValue('user', 'email','id', $email, $_SESSION['user']['id']);

                            $fetch_utilisateur = $modelProfil->selectAllWhere('user','id',$_SESSION['user']['id']); // je trouve mon id en dehors des session 
                            $_SESSION['user'] = $fetch_utilisateur;
                            echo "changement(s) effectué(s) email";
                    }else{
                        $errorLog = "Cet email est déjà utilisé par un autre utilisateur";
                    }
                }
            }

            if(!empty($image))
            {
                    $fetch_utilisateur = $modelProfil->selectAllWhere('user','login',$login); // je trouve mon id en dehors des session 

                            $modelProfil->updateOneValue('user', 'image','id', $image, $_SESSION['user']['id']);

                            $fetch_utilisateur = $modelProfil->selectAllWhere('user','id',$_SESSION['user']['id']); // je trouve mon id en dehors des session 
                            $_SESSION['user'] = $fetch_utilisateur;
                            echo "changement(s) effectué(s) image";                       
            }
        }
        echo $errorLog;
    }
}