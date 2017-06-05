<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 22.4.2017.
 * Time: 16:28
 */

namespace bagy94;
require_once "connection.php";
require_once "utility/UserSession.php";
require_once "utility/Router.php";
spl_autoload_register(function ($class){
    //var_dump($class);
    $className= end(explode("\\",$class));
    if(file_exists("controller/$className.php")){
        //var_dump($className);
        require_once "controller/$className.php";
    }
    else if(file_exists("utility/$className.php")){
        //var_dump($className);
        require_once "utility/$className.php";
    }
    else if(file_exists("model/$className.php")){
        //var_dump($className);
        require_once "model/$className.php";
    }
    else if(file_exists("view/$className.php")){
        //var_dump($className);
        require_once "view/$className.php";
    }else if(file_exists("service/$className.php")){
        require_once "service/$className.php";
    }
});

use bagy94\utility\Router;
use bagy94\controller\Controller;

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
