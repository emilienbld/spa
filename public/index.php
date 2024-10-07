<?php
//
//use App\Controller\RootController;
//use Slim\Factory\AppFactory;
//
//require __DIR__ . '/../vendor/autoload.php';
//
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//
//$app = AppFactory::create();
//
//// Charger les routes
//$rootController = new RootController();
//$rootController->registerRoutes($app);
//
//$app->run();


use App\Controller\RootController;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$app = AppFactory::create();

// Charger les routes
$rootController = new RootController();
$rootController->registerRoutes($app);

// Redirige vers la page de connexion
$app->get('/', function ($request, $response) {
    return $response->withHeader('Location', '/assets/connexion/connexion.html')->withStatus(302);
});

$app->run();
