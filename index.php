<?php

use App\controller\HomeController;
use App\controller\TvController;
use App\controller\MovieController;
use App\controller\SearchController;
use App\controller\ReviewController;
use App\controller\TmdbapiController;
use App\controller\FavoriteController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\controller\InscriptionController;

require __DIR__ . '/vendor/autoload.php';

session_start();

spl_autoload_register(function ($className) {
    $className = str_replace('App', 'src', $className);
    $filePath =  str_replace('\\', '/', $className) . '.php';
    if (file_exists($filePath)) {
        require($filePath);
    }
});

$app = AppFactory::create();

// Adapt rooter to sub directory
define('BASE_PATH', rtrim(dirname($_SERVER["SCRIPT_NAME"]), '/'));
define('HTTP_HOST', $_SERVER["HTTP_HOST"]);
$app->setBasePath(BASE_PATH);


// Add Routing Middleware
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', HomeController::class . ':main');

$app->get('/movie/{id}', MovieController::class . ':show');

$app->get('/movies', MovieController::class . ':main');

$app->get('/tv/{id}', TvController::class . ':show');

$app->get('/tv', TvController::class . ':main');

$app->get('/favorite', FavoriteController::class . ':main');

$app->get('/search/{keywords}', SearchController::class . ':results');

$app->map(['GET', 'POST'], '/inscription', HomeController::class . ':getInscription');

$app->map(['GET', 'POST'], '/connexion', HomeController::class . ':getConnexion');

$app->get('/log-out', HomeController::class . ':logOut');


$app->map(['GET', 'POST'], '/profil', HomeController::class . ':getProfil');

$app->map(['GET', 'POST'], '/review/{type}/{id}', ReviewController::class . ':get');

$app->post("/review/new/{type}/{id}", ReviewController::class .':add');


$app->get('/token/get', TmdbapiController::class . ':getToken');

$app->post('/token/set', TmdbapiController::class . ':setToken');

$app->get('/session/get', TmdbapiController::class . ':getSession');

$app->post('/session/set', TmdbapiController::class . ':setSession');

$app->run();
