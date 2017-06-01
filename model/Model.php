<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 5.5.2017.
 * Time: 1:29
 */

namespace bagy94\model;
require_once "MetaModel.php";
require_once "IModel.php";

abstract class Model extends MetaModel implements IModel
{

    public static $t="table";
    public static $tId = "id";
    public static $tDeleted = "deleted";
    public static $tCreatedAt="created_at";

    public function __construct($id=NULL,$data=array())
    {
        if(!is_null($data) && count($data)){
            $this->data = $data;
        }else if(!is_null($id) && is_numeric($id)){
            $this->set(self::$tId,$id);
            $this->init();
        }
        parent::__construct(null);
    }

    /**
     * Insert | Update object in Database
     * @param array $columnsToSave
     */
    function save($columnsToSave = array())
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

    }

    /**
     * Initialize object from Database
     * @param array $columnsToInitBy
     * @return bool
     */
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

    }
    /**
     * Saves the query we want to execute next
     * @param string $query
     */
    function setQuery($query){
        $this->query = $query;
    }
    /**
     * Saves params for saved query
     * @param null $queryParams
     */
    public function setQueryParams($queryParams)
    {
        $this->queryParams = $queryParams;
    }

    /**
     * @param null $columns
     * @param string $constraint
     * @param string $options
     * @return mixed
     */
    static function getAll($columns=NULL, $constraint="", $options=""){
        $class = get_called_class();
        $table = $class::$t;
        $connection = self::getInstance();
        $query = self::makeQuery("SELECT",[$table],$columns,$constraint,$options);
        $stm = self::execute($query,$connection);
        unset($connection);
        return $stm;
    }

}