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
    const DIR_ROOT = "WebDiP2016x005";
    const ROUTE = "req";

    private $reqParts=[];
    private $path=NULL;
    private static $_instance=NULL;

    function __construct()
    {
        $urlArrray = explode("/",$_SERVER["SCRIPT_NAME"]);
        $this->reqParts = array_slice($urlArrray, 0, array_search(self::DIR_ROOT, $urlArrray, true) +1);
    }

    public function build()
    {
        $this->path = implode("/",$this->reqParts);
    }

    public function buildRoot($HTTPS=FALSE)
    {
        if(is_null($this->path)){
            $this->build();
        }
        return $HTTPS?
            sprintf("https://%s%s/", $_SERVER["HTTP_HOST"], $this->path):
            sprintf("http://%s%s/", $_SERVER["HTTP_HOST"], $this->path);
    }

    public function buildLink($afterRootPath,$HTTPS=FALSE)
    {
        $url = $this->buildRoot($HTTPS);
        return $url.$afterRootPath;
    }

    public function buildActionLink($controller,$action,$params=NULL,$HTTPS=FALSE)
    {

        return $HTTPS?
            $this->buildLink(
                sprintf("?%s",
                    http_build_query(
                        [self::ROUTE=>
                            implode("/",
                                [$controller, $action,$params]
                            )
                        ]
                    )
                ),$HTTPS)
            :
            $this->buildLink(
                sprintf("%s/%s/%s", $controller, $action, $params),
                $HTTPS);
    }

    public static function Instance()
    {
        if(self::$_instance === NULL){
            self::$_instance = new Router();
        }
        return self::$_instance;
    }


    public static function buildRoute($path,$forceHTTPS=FALSE)
    {

        return self::Instance()->buildLink($path,$forceHTTPS);
        /*$urlArrray = explode("/",$_SERVER["SCRIPT_NAME"]);
        $url = implode(
            "/",
            array_slice($urlArrray, 0,
                array_search(self::DIR_ROOT, $urlArrray, true) +1));
        return $forceHTTPS?
            sprintf("https://%s%s/%s", $_SERVER["HTTP_HOST"], $url, $path):
            sprintf("http://%s%s/%s", $_SERVER["HTTP_HOST"], $url, $path);*/
    }


    public static function make($controller,$action,$args = NULL,$forceHTTPS=FALSE){

        return self::Instance()->buildActionLink($controller,$action,$args,$forceHTTPS);
        /*$param = isset($args)?$args:"";
        return $forceHTTPS?
            self::buildRoute(sprintf("?%s", http_build_query([self::ROUTE=>implode("/", [$controller, $action, $param])])),$forceHTTPS):
            self::buildRoute(sprintf("%s/%s/%s", $controller, $action, $param),$forceHTTPS);*/
    }
    public static function reqHTTPS($controller,$action,$getParams = NULL){
        if( !isset($_SERVER["HTTPS"])){
            $url =self::make($controller,$action,$getParams,TRUE);
            //echo $url;
            header("Location: $url");
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
        return ['controller'=>$key, 'action'=>$action,'args'=>$args];
    }

    public static function js($filename)
    {
        return isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on'?
            self::buildRoute("view/js/$filename.js",TRUE):
            self::buildRoute("view/js/$filename.js");
    }

    public static function css($filename)
    {
        return isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on'?
            self::buildRoute("view/css/$filename.css",TRUE):
            self::buildRoute("view/css/$filename.css");
    }

    public static function asset($filename,$ext="png")
    {
        return isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on'?
            self::buildRoute("view/asset/$filename.{$ext}",TRUE):
            self::buildRoute("view/asset/$filename.{$ext}");
    }
}