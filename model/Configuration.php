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


    private static $ID = 1;
    private static $_instance = NULL;

    const QUERY_TIME = "SELECT `interval` FROM `system_config` WHERE `id`=1";
    const QUERY_NUM_OF_ROWS_IN_TABLE = "SELECT `no_rows` FROM `system_config` WHERE `id`=1";
    const QUERY_ALL = "SELECT `interval`,`no_rows` FROM `system_config` WHERE `id`=1";

    public static $QUERY_INIT_BY_ID = "SELECT * FROM `system_config` WHERE id=1";

    public static $t = "system_config";
    public static $tId = "id";
    public static $tInterval = "interval";
    public static $tTableRows = "no_rows";
    public static $tActivationLinkDuration = "activation_link_duration";
    public static $tMaxLogIn = "max_login";

    protected $id,$interval,$activation_link_duration,$no_rows,$max_login;

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

    function getNew(){
        $this->init();
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
     * @return false|int
     */
    function currentTimestamp(){
        return strtotime("$this->interval hours");
    }
}