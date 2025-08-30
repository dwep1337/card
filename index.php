<?php
require __DIR__ . '/vendor/autoload.php';

use App\Router\Router;

//start router
$router = new Router();

//start dotenv support
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//routers
$router->add('GET', '/', 'HomeController@index');
$router->add("POST", "/check-card", "CardController@checkCard");

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);