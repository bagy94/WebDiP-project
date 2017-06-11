<?php
define("CSS_BASE","view/css/base.css");
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 05.06.17.
 * Time: 21:55
 */
require_once "../external_libs/smarty/libs/Smarty.class.php";
spl_autoload_register(function ($class){
    //var_dump($class);
    $className= end(explode("\\",$class));
    if(file_exists("../controller/$className.php")){
        //var_dump($className);
        require_once "../controller/$className.php";
    }
    else if(file_exists("../utility/$className.php")){
        //var_dump($className);
        require_once "../utility/$className.php";
    }
    else if(file_exists("../model/$className.php")){
        //var_dump($className);
        require_once "../model/$className.php";
    }
    else if(file_exists("../view/$className.tpl")){
        //var_dump($className);
        require_once "../view/$className.tpl";
    }else if(file_exists("../service/$className.php")){
        require_once "../service/$className.php";
    }
});
use bagy94\controller\PrivateController;
use bagy94\utility\Router;
if($_SERVER["HTTPS"] === 'on'){

}

$request = filter_input(INPUT_GET, Router::ROUTE,FILTER_SANITIZE_URL);
$urlParts = explode("/",$request);
$controller = new PrivateController();
$action = $urlParts[0] !== ''?$urlParts[0]:"index";
$args = count($urlParts)>1?array_slice($urlParts,1):NULL;
//var_dump($action);
if($controller->hasAction($action)){
    $response = $controller->invokeAction($action,$args);
    //var_dump($response);
}else{
    $response = $controller->error("Action not found");
}
//var_dump($response);
$response->show();
