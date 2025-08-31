<?php
require __DIR__ . '/vendor/autoload.php';

use App\Middleware\AuthMiddleware;
use App\Router\Router;

//start router
$router = new Router();

//start dotenv support
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//routers
$router->add('GET', '/', [], 'HomeController@index');
$router->add("POST", "/check-card", [], "CardController@checkCard");
$router->add("GET", "/login", [], "LoginController@index");
$router->add("POST", "/login", [], "LoginController@login");

//protected routes
$router->add("GET", "/admin/dashboard", [AuthMiddleware::class], "DashboardController@index");
$router->add("POST", "/logout", [AuthMiddleware::class], "DashboardController@logout");
$router->add('GET', '/cards', [AuthMiddleware::class], 'CardController@getCards');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);