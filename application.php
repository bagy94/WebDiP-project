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


use bagy94\controller\AboutController;
use bagy94\controller\DocumentationController;
use bagy94\controller\HomeController;
use bagy94\controller\LogInController;
use bagy94\controller\RegistrationController;
use bagy94\utility\WebPage;
$errorPage = new WebPage("view/error.tpl","Greška");




function callController($controller,$action,$args=NULL){
    switch ($controller){
        case "home":
           $active = new HomeController();
            break;
        case "login":
            $active = new LogInController();
            break;
        case "registration":
            $active = new RegistrationController();
            break;
        case "about":
            $active = new AboutController();
            break;
        case "doc":
            $active = new DocumentationController();
            break;
        default:
            showError("404 page not found");
            return;
    }
    if($active->hasAction($action)){
        $active->invoke($action,NULL);
    }else{
        showError("420 Action not found");
    }
}

function showError($message){
    global $errorPage;
    $errorPage->assign("message",$message);
    $errorPage->show();
}