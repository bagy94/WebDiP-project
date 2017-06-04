<?php

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 03.06.17.
 * Time: 19:50
 */
namespace bagy94\model;
require_once "db/Db.php";
use bagy94\utility\db\Db;
use bagy94\utility\UserSession;

class Log
{
    const KEYWORD_VISIT = "visit";
    const KEYWORD_SERVICE = "service";
    const KEYWORD_ACTION = "action";



    private static $_instance = NULL;

    private $db;

    private function __construct()
    {
        $this->db = new Db();
    }

    private static function Instance(){
        if(is_null(self::$_instance)){
            self::$_instance = new Log();
        }
        return self::$_instance;
    }

    public static function write($keyword,$content,$user)
    {
        return self::Instance()->db->writeLog($keyword,$content,$user);
    }

    public static function get($limit=NULL,$offset)
    {
        $options = isset($lmit)?Db::limit($limit):"";
        $options .= isset($offset)?Db::offset($offset):"";
        return self::Instance()->db->getLogs($options);
    }

    public static function visit($page,$user=NULL){
        $user = !isset($user)?UserSession::log():$user;
        self::write(self::KEYWORD_VISIT,"Pregled stranice: $page",$user);
    }
    public static function service($service,$user=NULL){
        $user = !isset($user)?UserSession::log():$user;
        self::write(self::KEYWORD_SERVICE,"Upotreba servisa: $service",$user);
    }
    public static function action($action,$user=NULL){
        $user = !isset($user)?UserSession::log():$user;
        self::write(self::KEYWORD_ACTION,"Akcija: $action",$user);
    }

    /*public static function db($query,$user,$params=NULL)
    {
        //self::Instance()->db->writeLog("db",)
    }*/
}