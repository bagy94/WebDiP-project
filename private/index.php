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

use bagy94\utility\WebPage;
use bagy94\utility\Router;
use bagy94\model\User;
$router = new WebPage();
$user = new User();
$router->buildProjectRoot();
$router->build();
$loader = $router->buildLink("loader.php");