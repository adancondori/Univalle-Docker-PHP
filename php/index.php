<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__ . '/Http/Router.php';
require __DIR__ . '/Controllers/PersonController.php';
//require __DIR__ . '/../src/controllers/UserController.php';

$router = new Router();

// Define las rutas
$router->add('/api/person', 'GET', PersonController::class, 'index');
$router->add('/api/person/create', 'POST', PersonController::class, 'create');
#$router->add('/api/user', 'GET', [UserController::class, 'index']);
#$router->add('/api/user', 'POST', [UserController::class, 'store']);

// Procesar la solicitud
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
