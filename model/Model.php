<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 5.5.2017.
 * Time: 1:29
 */

namespace bagy94\model;
use bagy94\utility\db\Db;

require_once "MetaModel.php";
require_once "IModel.php";

abstract class Model extends MetaModel implements IModel
{
    public static $QUERY_INSERT;
    public static $QUERY_UPDATE;
    public static $QUERY_INIT_BY_ID;


    public static $t="table";
    public static $tId = "id";
    public static $tDeleted = "deleted";
    public static $tCreatedAt="created_at";


    public function __construct($id=NULL,$data=NULL)
    {
        if(!is_null($data)){
            $this->initData($data);
        }
        else if (!is_null($id)){

        }
    }

    /**
     * Insert | Update object in Database
     * @param array $columnsToSave
     */
    /*function save($columnsToSave = array())
    {
        if( !(is_array($columnsToSave) && count($columnsToSave))){
            $columnsToSave = $this->columns();
        }
        if(isset($this->data[self::$tId])){
            $query = "UPDATE `".self::$t."` SET ";
            $k = count($columnsToSave);
            $params = array();
            for ($i = 0; $i < $k; $i++) {
                if(!self::isFunction($this->get($columnsToSave[$i]))){
                    $param = self::dbQueryPrepPrefix.$columnsToSave[$i];
                    $query .= "`$columnsToSave[$i]` = {$param}";
                    $params[$param] = $this->get($columnsToSave[$i]);
                }else{
                    $query .= "`$columnsToSave[$i]` =".$this->get($columnsToSave[$i]);
                }
                if(($i+1)<$k){
                    $query .= ",";
                }
            }

        }else{
            $query = "INSERT INTO `".self::$t."` VALUES (DEFAULT,";
            $k = count($columnsToSave);
            $params = array();
            for ($i = 0; $i < $k; $i++) {
                if(!self::isFunction($this->get($columnsToSave[$i]))){
                    $param = self::dbQueryPrepPrefix.$columnsToSave[$i];
                    $query .= "{$param}";
                    $params[$param] = $this->get($columnsToSave[$i]);
                }else{
                    $query .= $this->get($columnsToSave[$i]);
                }
                if(($i+1)<$k){
                    $query .= ",";
                }
            }
        }
        $this->query = $query. ")";
        $this->queryParams = $params;
        //print_r($this->query);
        //print_r($this->queryParams);

        /*$this->connect();
        $this->prepare();
        $this->query();
        $this->disconnect();
        return $this->dbResult->success;*/

    //}

    /*
     * Initialize object from Database
     * @param array $columnsToInitBy
     * @return bool

    function init($columnsToInitBy=array())
    {
        $this->query = "SELECT * FROM `".self::$t."` WHERE ";
        if(!(is_array($columnsToInitBy) && count($columnsToInitBy))){
            $columnsToInitBy = array(self::$tId);
        }
        $k= count($columnsToInitBy);
        $this->queryParams = array();
        for ($i = 0; $i < $k; $i++) {
            $param = self::dbQueryPrepPrefix.$columnsToInitBy[$i];
            $this->query .= "`{$columnsToInitBy[$i]}`={$param}";
            $this->queryParams[$param]= $this->get($columnsToInitBy[$i]);
            if(($i+1)<$k){
                $this->query .= " and ";
            }
        }
       // echo $this->query;
        $this->connect();
        $this->prepare();
        if($this->query()){
            //echo "<br>".print_r($this->dbResult->getData())."<br>";
            if($this->dbResult->hasData()){
                $this->data= $this->dbResult->getData()[0];
            }
            //echo "<br>Data: ";print_r($this->data);
            $this->disconnect();
            return $this->dbResult->success;
        }
        $this->disconnect();
        return null;

    }*/
    protected function buildQueryInitById(){
        self::$QUERY_INIT_BY_ID = "SELECT * FROM ".self::$t." WHERE ".self::$tId."= ?";
    }

    public function initData($dataRow)
    {
        foreach ($dataRow as $column=>$value){
            $this->$column = $value;
        }
    }

    public static function initBy($query,$params){
        $db = new Db($query,$params,Db::getInstance());
        if ($db->runQuery() && $db->getStm()->rowCount()){
            $model = $db->getStm()->fetchObject(__CLASS__);
        }else{
            $model = NULL;
        }
        $db->disconnect();
        return $model;
    }

    /**
     * @param null $columns
     * @param string $constraint
     * @param string $options
     * @return mixed
     */
    public static function getAll($columns=NULL, $constraint="", $options=""){
        $class = get_called_class();
        $table = $class::$t;
        $connection = Db::getInstance();
        $query = Db::makeQuery("SELECT",[$table],$columns,$constraint,$options);
        $stm = Db::execute($query,$connection);
        unset($connection);
        return $stm;
    }

}