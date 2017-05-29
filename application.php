<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:53
 */
namespace bagy94;
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
});



use bagy94\controller\HomeController;
use bagy94\controller\LogInController;
use bagy94\controller\RegistrationController;
require_once "connection.php";


function callController($controller,$action,$args=NULL){
    switch ($controller){
        case "home":
           $active = new HomeController();
            break;
        case "login":
            $active = new LogInController();
            break;
    }
    if($active->hasAction($action)){
        $active->$action();
    }
}