<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:32
 */

require_once "controller/HomeController.php";

use bagy94\webdip\wellness\controller\AboutController;
use bagy94\webdip\wellness\controller\HomeController as Home;
use bagy94\webdip\wellness\controller\LogInController;
use bagy94\webdip\wellness\controller\RegistrationController;

$controllers = [
    Home::$CONTROLLER=>[],
    //LogInController::$CONTROLLER=>[],
    //RegistrationController::$CONTROLLER=>[],
    "about",
    "doc"
];


if(array_key_exists($controller,$controllers)){
    callController($controller,$action);

}else{
    $error = "Page not fount";
    require_once("view/error.php");
}


/**
 * @param $controller
 * @param $action
 */
function callController($controller, $action){
    switch ($controller){
        case 'login':
            $controller = new LogInController();
            break;
        case 'registration':
            $controller = new RegistrationController();
            break;
        case "about":
            $controller = new AboutController();
        default:
            $controller = new Home();

    }
    if($controller->hasAction($action)){
        $controller->{$action}();
    }else{
        $error = "Action not found";
        require_once("view/error.php");
    }

}