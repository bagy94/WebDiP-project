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
}else{
    $route = "home/index";
}
$request = Router::decode($route);
callController($request['controller'],$request['action'],$request['args']);