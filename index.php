<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 22.4.2017.
 * Time: 16:28
 */

namespace bagy94;
require_once "loader.php";
require_once "connection.php";
require_once "utility/UserSession.php";
require_once "utility/Router.php";


use bagy94\utility\Router;
use bagy94\controller\Controller;

//var_dump($_GET);
//var_dump($_POST);
if(isset($_GET[Router::ROUTE])){
    $route = filter_input(INPUT_GET,Router::ROUTE,FILTER_SANITIZE_URL);
    $route = $route === ""?"home/index":$route;
}
else{
    $url = Router::make("home",NULL);
    header("Location: $url");
    //echo "bla3";
}
$request = Router::decode($route);
Controller::invokeController($request);
