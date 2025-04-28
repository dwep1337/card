<?php
require __DIR__ . '/vendor/autoload.php';

use App\Router\Router;

//start router
$router = new Router();


$router->add('GET', '/', 'HomeController@index');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);