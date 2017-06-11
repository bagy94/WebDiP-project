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

class Log extends Model
{
    public static $t = "sys_log";


    public static $tId = "log_id";
    public static $tActionId = "action_id";
    public static $tUserCreatedId = "user_id";
    public static $tContent = "content";

    private $log_id,$action_id,$user_id,$content;

    private static $_instance=NULL;

    public function __construct()
    {
        parent::__construct(NULL,NULL);
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
        return self::Instance()->connect()->writeLog($actionId,$userid,$content);
    }

    public static function get($limit=NULL,$offset)
    {
        $options = isset($lmit)?Db::limit($limit):"";
        $options .= isset($offset)?Db::offset($offset):"";
        return self::Instance()->db->getLogs($options);
    }











    // Getters and setters
    /**
     * @return mixed
     */
    public function getLogId()
    {
        return $this->log_id;
    }
    /**
     * @param mixed $log_id
     * @return Log
     */
    public function setLogId($log_id)
    {
        $this->log_id = $log_id;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getActionId()
    {
        return $this->action_id;
    }
    /**
     * @param mixed $action_id
     * @return Log
     */
    public function setActionId($action_id)
    {
        $this->action_id = $action_id;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }
    /**
     * @param mixed $user_id
     * @return Log
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * @param mixed $content
     * @return Log
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
}