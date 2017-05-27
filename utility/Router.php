<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 13.5.2017.
 * Time: 19:06
 */

namespace bagy94\webdip\wellness\utility;

spl_autoload_register(function ($className){
    require_once "controller/$className.php";
});


class Router
{
    private static $routes = [
        'home'=>"HomeController",
        'login'=>"LogInController",
        'registration'=>"RegistrationController",
        'about'=>"AboutController",
        'doc'=>"DocumentationController"
    ];
    const DIR_ROOT = "WebDiP2016x005";
    const ROUTE = "req";

    public static function make($controller,$action,$args = NULL){
        $urlArrray = explode("/",$_SERVER["REQUEST_URI"]);
        $url = implode(
            "/",
            array_slice(
                $urlArrray,
                0,
                array_search(
                    self::DIR_ROOT,
                    $urlArrray,
                    true)
                +1)
        );
        if( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on' ){
            return sprintf("?%s",
                http_build_query([
                    self::DIR_ROOT=>implode("/", [$controller, $action, http_build_query($args)]
                    ),
                ]));
        }else{
            return sprintf("http://%s/%s/%s",
                $_SERVER["HTTP_HOST"].$url,
                $controller,
                $action,
                http_build_query($args));

        }
    }
    public static function reqHTTPS(){
        if( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on' ){
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
            exit();
        }
    }

    public static function redirect($route)
    {
        $request = explode("/",$route);
        if(isset($route[0]) && array_key_exists($route[0],self::$routes)){
            $controller = new self::$routes[$route[0]]();
        }else{
            $controller = new ErrorController();
        }

    }
}