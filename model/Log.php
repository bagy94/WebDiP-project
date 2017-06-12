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
    const SELECT_VIEW = "SELECT * FROM admin_log";
    public static $t = "sys_log";


    public static $tId = "log_id";
    public static $tActionId = "action_id";
    public static $tUserCreatedId = "user_id";
    public static $tContent = "content";

    public static $tViewAction = "action";
    public static $tViewCategory= "category";
    protected $log_id, $action_id, $user_id, $content;
    protected $action,$category;

    private static $_instance = NULL;

    public function __construct()
    {
        parent::__construct(NULL, NULL);
    }

    private static function Instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Log();
        }
        return self::$_instance;
    }

    public static function write($actionId, $content, $userid = NULL)
    {
        if (!$userid) {
            $userid = UserSession::log();
        }
        return self::Instance()->connect()->writeLog($actionId, $userid, $content);
    }

    public static function get($limit = NULL, $offset)
    {
        $options = isset($lmit) ? Db::limit($limit) : "";
        $options .= isset($offset) ? Db::offset($offset) : "";
        return self::Instance()->db->getLogs($options);
    }

    /**
     * @param null $srch
     * @param null $constraintcolumns
     * @param int $limit
     * @param int $offset
     * @param int $sort
     * @return Log[]
     */
    public static function getAdminView($srch = NULL, $constraintcolumns=NULL, $limit = 30, $offset=0, $sort = 1)
    {
        $class = get_called_class();
        $db = new Db(self::SELECT_VIEW);
        //var_dump($constraintcolumns);
        if(isset($constraintcolumns) && isset($srch)){
            $data = [$constraintcolumns=>$srch];
            //var_dump($data);
            $db->Where()->Like($data);
        }
        //var_dump($offset);
        if($offset < 0){
            $db->Sort($sort,TRUE)->Limit(0,$limit);
        }else{
            $db->Sort($sort)->Limit($offset,$limit);
        }
        //var_dump($db->getQuery());
        //var_dump($data);
        if($db->runQuery()){
            $data = $db->getStm()->fetchAll(\PDO::FETCH_CLASS,$class);
        }

        //var_dump($data);
        $db->disconnect();
        return $data;
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
    public function setActionId($action_id,$asObject=FALSE)
    {
        if($asObject){
            $this->action_id = new LogAction($action_id);
            return $this;
        }
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
    public function setUserId($user_id,$asObject=FALSE)
    {
        if($asObject){
            $this->user_id = new User($user_id);
            return $this;
        }
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