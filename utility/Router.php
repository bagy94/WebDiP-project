<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 13.5.2017.
 * Time: 19:06
 */

namespace bagy94\utility;
class Router
{
    public static $controllers = array(
        'home',
        'login',
        'registration',
        'about',
        'doc'
    );
    const DIR_ROOT = "WebDiP2016x005";
    const ROUTE = "req";

    public static function make($controller,$action,$args = NULL){
        $urlArrray = explode("/",$_SERVER["REQUEST_URI"]);
        $param = is_null($args)?"":"?".http_build_query($args);
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
                    self::ROUTE=>implode("/", [$controller, $action, $param]
                    ),
                ]));
        }else{
            $url =  sprintf("http://%s/%s/%s",
                $_SERVER["HTTP_HOST"].$url,
                $controller,
                $action,
                $param);
            return $url;
        }
    }
    public static function reqHTTPS(){
        if( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on' ){
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
            exit();
        }
    }

    public static function decode($route)
    {
        $request = explode("/",$route);
        //print_r($request);
        $key = isset($request[0])?$request[0]:"error";
        $action = isset($request[1])?$request[1]:"index";
        $args = isset($request[2])?$request[2]:NULL;
        if(in_array($key,self::$controllers)){
            return [
                'controller'=>$key,
                'action'=>$action,
                'args'=>$args
            ];
        }else{
            return [
                'controller'=>NULL,
                'action'=>NULL,
                'args'=>"404 Page not found"
            ];
        }
    }

    public static function js($filename)
    {

    }

    public static function css($filename)
    {

    }
}