<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 13.5.2017.
 * Time: 19:06
 */

namespace bagy94\webdip\wellness\utility;


class Route
{
    const DIR_ROOT = "WebDiP2016x005";
    const PARAM_GET_CONTROLLER = "controller";
    const PARAM_GET_ACTION = "action";

    public static function make($controller,$action){
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
            return sprintf("https://%s/?%s",
                $_SERVER["HTTP_HOST"].$url,
                http_build_query([
                    self::PARAM_GET_CONTROLLER=>$controller,
                    self::PARAM_GET_ACTION =>$action
                ]));
        }else{
            return sprintf("http://%s/%s/%s",
                $_SERVER["HTTP_HOST"].$url,
                $controller,
                $action);

        }
    }
    public static function decodeURL($route){
        return explode("/",$route);
    }
    public static function reqHTTPS(){
        if( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on' ){
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
            exit();
        }
    }
}