<?php

namespace App\controller;

class InscriptionController extends Controller
{
    // public $login;
    // public $email;
    // public $password;
    // public $confirm_password;

    public function register($login, $email,$password, $confirm_password)
    {
        $modelInscription = new \App\Model\InscriptionModel();
        $controllerInscription = new \App\Controller\InscriptionController();

        $login = $controllerInscription->secure($login);
        $password = $controllerInscription->secure($password);        //securisé    
        $email = $controllerInscription->secure($email);
        $confirm_password = $controllerInscription->secure($confirm_password);                 // récupération valeurs $_POST, son droit est utilisateur a l'inscription

        $errorLog = null;
        if (!empty($login) && !empty($password) && !empty($confirm_password) && !empty($email)) { // si les champs sont vides alors $errorLog

            $login_len = strlen($login);
            $password_len = strlen($password);
            $confirm_password_len = strlen($confirm_password);
            $email_len = strlen($email);
            if (($login_len >= 2) && ($password_len >= 3) && ($confirm_password_len >= 3) && ($email_len>=7)) 
            { // limite minimum de caractere

                if (($login_len <= 30) && ($password_len <= 30) && ($confirm_password_len <= 30) && ($email_len<=30)) 
                { // limite maximum de caractere
                    $existLogin = $modelInscription->alreadyTakenCheck('user','login',$login); // l'utilisateur existe-t-il ? 
                    $existEmail = $modelInscription->alreadyTakenCheck('user','email',$email); // l'email est-il déjà utilisé ?
                    if (!$existLogin) 
                    {
                        if (!$existEmail) 
                        {
                            if ($confirm_password == $password) // si le mdp != confirm mdp alors $errorLog
                            {
                                
                                $cryptedpass = password_hash($password, PASSWORD_BCRYPT); // CRYPTED 
                                $modelInscription->createAccount($login, $email,$cryptedpass);

                                $this->redirect('connexion'); // GG WP
                                
                            } else $errorLog = "<p>Confirmation du mot de passe incorrect</p>".$confirm_password ." ".$password ;
                            
                        } else $errorLog = "<p>Email déjà utilisé</p>";
                        
                    } else $errorLog = "<p>Identifiant déjà utilisé</p>";
                    
                } else $errorLog = "<p>mdp et identifiant limités a 30 caractères</p>";
                
            } else $errorLog = "<p>login, doit avoir 2 caracteres minimum, le mdp doit avoir 5 caracteres minimum</p>";
            
        } else  {$errorLog = "<p>Champs non remplis</p>";
                }
    echo $errorLog;
    }



}
