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
use bagy94\controller\ThemeService;
use bagy94\utility\WebPage;
use bagy94\utility\Response;




function callController($controller,$action,$args=NULL){
    //echo "controller:".$controller;
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
        case "theme":
            $active = new ThemeService();
            break;
        default:
            return showError("404 page not found");
    }
    if($active->hasAction($action)){
        return $active->invoke($action,NULL);
    }else{
        return showError("420 Action not found");
    }
}

function showError($message){
    $errorPage = new WebPage("view/error.tpl","Greška");
    $errorPage->assign("message",$message);
    return new Response($errorPage->getHTML());
}