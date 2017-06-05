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
    private static $_instance = NULL;

    private $db;
    private $stm;
    private $user;

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

    public static function write($actionId,$content,$userid=NULL)
    {
        if(!$userid){
            $userid = UserSession::log();
        }
        return self::Instance()->db->writeLog($actionId,$userid,$content);
    }

    public static function get($limit=NULL,$offset)
    {
        $options = isset($lmit)?Db::limit($limit):"";
        $options .= isset($offset)?Db::offset($offset):"";
        return self::Instance()->db->getLogs($options);
    }
    /*public static function db($query,$user,$params=NULL)
    {
        //self::Instance()->db->writeLog("db",)
    }*/
}