<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 22.4.2017.
 * Time: 16:28
 */

namespace bagy94;

require_once "application.php";
use bagy94\utility\Router;

if(isset($_GET[Router::ROUTE])){
    $route = filter_input(INPUT_GET,Router::ROUTE,FILTER_SANITIZE_URL);
    $route = $route === ""?"home/index":$route;
}
else{
    $url = Router::make("home","index");
    header("Location: $url");
    //echo "bla3";
}

$request = Router::decode($route);
callController($request['controller'],$request['action'],$request["args"])->show();
