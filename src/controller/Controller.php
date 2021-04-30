<?php

namespace App\controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    protected $twig;

    public function preloadTwig()
    {
        $loader = new FilesystemLoader('view');
        $this->twig = new Environment($loader);

        // Global variables used in all templates
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal("BASE_PATH", BASE_PATH);
        $this->twig->addGlobal("HTTP_HOST", HTTP_HOST);
    }
    public function secure($var) // le sang de la veine
    {
        $var = htmlspecialchars(trim($var));
        return $var;
    }
    public function redirect(string $path)
    {

        header('Location: ' . $path);
        exit();
    }
}
