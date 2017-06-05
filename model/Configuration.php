<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 03.06.17.
 * Time: 10:59
 */
namespace bagy94\model;
require_once "Model.php";

class Configuration extends Model
{
    const TIME_TYPE_MINUTES = 2;
    const TIME_TYPE_SEC = 1;
    const TIME_TYPE_DAYS = 3;
    const TIME_TYPE_MONTHS = 4;
    const TIME_TYPE_HOURS = 0;

    private static $ID = 1;
    private static $_instance = NULL;

    const QUERY_TIME = "SELECT `interval` FROM `sys_config` WHERE `id`=1";
    const QUERY_NUM_OF_ROWS_IN_TABLE = "SELECT `no_rows` FROM `sys_config` WHERE `id`=1";
    const QUERY_ALL = "SELECT `interval`,`no_rows` FROM `sys_config` WHERE `id`=1";

    public static $QUERY_INIT_BY_ID = "SELECT * FROM `sys_config` WHERE id=1";

    public static $t = "sys_config";
    public static $tId = "id";
    public static $tInterval = "interval";
    public static $tTableRows = "no_rows";
    public static $tActivationLinkDuration = "activation_link_duration";
    public static $tMaxLogIn = "max_login";
    public static $tCookieDuration = "cookie_duration";
    public static $tSessionDuration = "session_duration";
    public static $tLogInCodeDuration = "login_code_duration";
    public static $tRealSessionEnd = "real_time_session_end";

    protected $id,$interval,$activation_link_duration,$no_rows,$max_login,$cookie_duration,$session_duration,$login_code_duration,$real_time_session_end;




    public function __construct($data = NULL)
    {
        parent::__construct(self::$ID, $data);
    }

    public static function Instance()
    {
        if(self::$_instance === NULL){
            self::$_instance = new Configuration();
        }
        return self::$_instance;
    }
    function interval(){
        return $this->interval;
    }

    /**
     * Return real time end.
     * @return int
     */
    public function getCookieEndTime()
    {
        return time()+(3600*((int)$this->getCookieDuration()));
    }

    /**
     * @return false|int
     */
    function currentTimestamp(){
        return strtotime("$this->interval hours");
    }

    /**
     * @param int $timeType
     * @return false|int
     */
    function sessionDuration($timeType = self::TIME_TYPE_HOURS){
        //return strtotime("$this->session_duration hours",$this->currentTimestamp());
        settype($this->session_duration,"int");
        switch ($timeType){
            case self::TIME_TYPE_SEC: return (int)$this->session_duration;
            case self::TIME_TYPE_MINUTES: return (int)$this->session_duration * 60;
            case self::TIME_TYPE_DAYS: return (int)$this->session_duration * 60 * 60 * 24;
            case self::TIME_TYPE_MONTHS:return (int)$this->session_duration *60* 60 * 24 * 30;
            default:
                return (int)$this->session_duration * 3600;
        }
    }

    function sessionRealTimeDuration($timeType = self::TIME_TYPE_HOURS){
        settype($this->real_time_session_end,"int");
        switch ($timeType){
            case self::TIME_TYPE_SEC: return (int)$this->real_time_session_end;
            case self::TIME_TYPE_MINUTES: return (int)$this->real_time_session_end * 60;
            case self::TIME_TYPE_DAYS: return (int)$this->real_time_session_end * 60 * 60 * 24;
            case self::TIME_TYPE_MONTHS:return (int)$this->real_time_session_end *60* 60 * 24 * 30;
            default:
                return (int)$this->real_time_session_end * 3600;
        }
    }














    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Configuration
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param mixed $interval
     * @return Configuration
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivationLinkDuration()
    {
        return $this->activation_link_duration;
    }

    /**
     * @param mixed $activation_link_duration
     * @return Configuration
     */
    public function setActivationLinkDuration($activation_link_duration)
    {
        $this->activation_link_duration = $activation_link_duration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNoRows()
    {
        return $this->no_rows;
    }

    /**
     * @param mixed $no_rows
     * @return Configuration
     */
    public function setNoRows($no_rows)
    {
        $this->no_rows = $no_rows;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxLogin()
    {
        return $this->max_login;
    }

    /**
     * @param mixed $max_login
     * @return Configuration
     */
    public function setMaxLogin($max_login)
    {
        $this->max_login = $max_login;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCookieDuration()
    {
        return $this->cookie_duration;
    }

    /**
     * @param mixed $cookie_duration
     * @return Configuration
     */
    public function setCookieDuration($cookie_duration)
    {
        $this->cookie_duration = $cookie_duration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSessionDuration()
    {
        return $this->session_duration;
    }

    /**
     * @param mixed $session_duration
     * @return Configuration
     */
    public function setSessionDuration($session_duration)
    {
        $this->session_duration = $session_duration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLoginCodeDuration()
    {
        return $this->login_code_duration;
    }

    /**
     * @param mixed $login_code_duration
     * @return Configuration
     */
    public function setLoginCodeDuration($login_code_duration)
    {
        $this->login_code_duration = $login_code_duration;
        return $this;
    }

    /**
     * @return int
     */
    public function getRealTimeSessionEnd()
    {
        return $this->real_time_session_end;
    }



}