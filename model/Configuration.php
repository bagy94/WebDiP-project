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

    protected $id,$interval,$no_rows;

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

    function getColumns()
    {
        // TODO: Implement getColumns() method.
    }

    function interval(){
        return $this->interval;
    }

    function getNew(){
        $this->init();
    }
}