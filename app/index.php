<?php
require "bootstrap.php";

use App\Controller\UserController;
use App\Controller\LoginController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// todos os endepoint começarão com user ou login
// tudo fora esses dois, retornará erro 404
if ( ! in_array($uri[1], ['user', 'login']) ) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// id é opcional e será SEMPRE numérico
$id = null;
if ( isset($uri[2]) ) {
    $id = (int) $uri[2];
}

// drink também é opcional e será boolean
// responsável pela cadastro da quantidade bebida
$drink = false;
if ( isset($uri[3]) && $uri[3]==='drink' ) {
    $drink = (bool) $uri[3];
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

// passando as variáveis para a Controller
// e executando as requisições:
$controller = null;
switch ($uri[1]) {
    case 'login':
        $controller = new LoginController($db, $requestMethod);
        break;

    case 'user':
        $controller = new UserController($db, $requestMethod, $id, $drink);
        break;

    default:
        header("HTTP/1.1 404 Not Found");
        exit();
}

$controller->processRequest();
