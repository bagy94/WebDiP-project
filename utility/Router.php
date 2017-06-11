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
    const SERVICE = "service";

    private $reqParts=[];
    private $path=NULL;
    private static $_instance=NULL;

    function __construct()
    {

        $this->reqParts = explode("/",$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"]));
        //
    }
    function buildProjectRoot(){
        $urlArrray = explode("/",$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]);
        $this->reqParts = array_slice($urlArrray, 0, array_search(self::DIR_ROOT, $urlArrray, true) +1);
        $this->build();
        return $this;
    }

    public function build()
    {
        $this->path = implode("/",$this->reqParts);
        return $this;
    }

    public function buildRoot($HTTPS=FALSE)
    {
        if(is_null($this->path)){
            $this->build();
        }
        //var_dump($this->path);
        return $HTTPS?
            sprintf("https://%s/", $this->path):
            sprintf("http://%s/", $this->path);
    }

    public function buildLink($afterRootPath,$HTTPS=FALSE)
    {
        $url = $this->buildRoot($HTTPS);
        return $url.$afterRootPath;
    }

    public function buildActionLink($controller,$action=NULL,$args=NULL,$HTTPS=FALSE)
    {
        $path = [$controller];
        if(isset($action)){
            array_push($path,$action);
        }
        if(isset($args)){
            array_push($path,$args);
        }
        //$url = sprintf("?%s=%s",self::ROUTE,implode("/",$path));
        //echo $url;
        return $HTTPS? $this->buildLink(sprintf("?%s=%s",self::ROUTE,implode("/",$path)),$HTTPS):
            $this->buildLink(sprintf("%s", implode("/",$path)), $HTTPS);
        /*return $HTTPS?
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
                sprintf("%s/%s%s", $controller, $action, $params),
                $HTTPS);*/
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


    public static function make($controller,$action=NULL,$args = NULL,$forceHTTPS=FALSE){

        return self::Instance()->buildActionLink($controller,$action,$args,$forceHTTPS);
        /*$param = isset($args)?$args:"";
        return $forceHTTPS?
            self::buildRoute(sprintf("?%s", http_build_query([self::ROUTE=>implode("/", [$controller, $action, $param])])),$forceHTTPS):
            self::buildRoute(sprintf("%s/%s/%s", $controller, $action, $param),$forceHTTPS);*/
    }
    public static function reqHTTPS($controller,$action=NULL,$getParams = NULL){
        if( !isset($_SERVER["HTTPS"])){
            $url =self::make($controller,$action,$getParams,TRUE);
            //echo $url;
            header("Location: $url");
            exit();
        }
    }

    public static function decode($route)
    {
        //print_r(explode("?",$route));
        $request = explode("/",$route);

        $key = isset($request[0])?$request[0]:"error";
        $action = isset($request[1])?$request[1]:NULL;
        $args = array_slice($request,2);
        //print_r(self::splitArgs($args));
        return ['controller'=>$key, 'action'=>$action,'args'=>$args];
    }

    public static function js($filename)
    {
        $url =  isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on'?
            self::buildRoute("view/js/$filename.js",TRUE):
            self::buildRoute("view/js/$filename.js");
        //echo $url;
        return $url;
    }

    public static function css($filename)
    {
        //echo  isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on';
        $url =  isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on'?
            self::buildRoute("view/css/$filename.css",TRUE):
            self::buildRoute("view/css/$filename.css");
        return $url;
    }

    public static function asset($filename,$ext="png")
    {
        return isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on'?
            self::buildRoute("view/asset/$filename.{$ext}",TRUE):
            self::buildRoute("view/asset/$filename.{$ext}");
    }

    public function testPrintPath(){
        $this->build();
        print_r($this->path);
        print_r($this->reqParts);
        return $this->buildRoot();
    }

    public static function splitArgs($args,$askeys=TRUE){
        return array_chunk($args,2,$askeys);
    }

    public static function templateDir()
    {
        return self::Instance()->buildLink("view");
    }
    public static function compileDir(){
        return self::Instance()->buildLink("templates_c");
    }

    public static function unforceHTTPS($controller,$action=NULL,$getParams = NULL)
    {
        if( isset($_SERVER["HTTPS"])){
            $url =self::make($controller,$action,$getParams,FALsE);
            //echo $url;
            header("Location: $url");
            exit();
        }
    }

    public static function retrieveJsonPostData()
    {
        // get the raw POST data
        $rawData = file_get_contents("php://input");
        // this returns null if not valid json
        return json_decode($rawData);
    }

}