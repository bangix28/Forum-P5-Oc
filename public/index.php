<?php
use App\Router;
use Tracy\Debugger;


require_once '../vendor/autoload.php';

if (session_status() == PHP_SESSION_NONE){
    session_start();
    $_SESSION['roles'] = "ROLES_GUEST";
}
$router = new Router();
Debugger::enable();
//var_dump($router);
/*
 * run application
 */
$router->run();

