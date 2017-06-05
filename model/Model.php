<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 5.5.2017.
 * Time: 1:29
 */

namespace bagy94\model;
use bagy94\utility\db\Db;
use bagy94\utility\UserSession;

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

    protected $created_at,$deleted=0;

    protected $sysConfig = NULL;

    public function __construct($id=NULL,$data=NULL)
    {
        self::$t = $this::$t;
        self::$tId = $this::$tId;
        if(!is_null($data)){
            $this->initData($data);
        }
        else if (!is_null($id)){
            $this->{self::$tId} = $id;
            $this->init();
        }

    }

    /**
     * Insert | Update object in Database
     * @param array $columnsToSave
     */
    function save($columnsToSave = array())
    {

    }

    protected function init(){
        $query = $this::$QUERY_INIT_BY_ID;
        //print $query;
        $id = $this->{self::$tId};
        if($this->connect($query,[$id])->prepare()->runQuery()){
            $data = $this->connection->getStm()->fetch(\PDO::FETCH_ASSOC);
            $this->initData($data);
        }
        $this->connection->disconnect();
        return isset($data);
    }

    protected function insert($values=[]){
        $this->connect();
        if($this->connection->makeInsert($this::$t,$values)->prepare()->runQuery()){
            $id = $this::$tId;
            $this->$id = $this->connection->lastId();
        }
        $this->connection->disconnect();
        return is_numeric($this->{self::$tId}) && $this->{self::$tId} !== "-1";
    }
    public function update($columns=[]){
        if( !(is_array($columns) && count($columns))){
            $columns = $this::getColumns();
        }
        $this->connect();
        foreach ($columns as $column){
            $foo[$column] = $this->{$column};
        }

        return $this->getConnection()
            ->makeUpdate($this::$t,$foo,[$this::$tId=>$this->{$this::$tId}])
            ->prepare()
            ->runQuery();



        /*$queryy = Db::makeQuery("UPDATE",[self::$t]);
        $query = "UPDATE `" . self::$t . "` SET ";
        $last = end($columns);
        foreach ($columns as $column) {
            $query .= "`$column`= ? ";
            if ($column !== $last) $query .= ", ";
            $this->connection->addParam($this->$column);
        }
        //$query = " WHERE "
        foreach ($constraintArray as $column){

        }*/
    }

    protected function columnsToConstraints($columns=[]){
        if(!count($columns)){
            $columns = $this->columns();
        }
    }

    protected function buildQueryInitById(){
        self::$QUERY_INIT_BY_ID = "SELECT * FROM ".self::$t." WHERE ".self::$tId."= ?";
    }

    public function initData($dataRow)
    {
        foreach ($dataRow as $column=>$value){
            $this->$column = $value;
        }
    }

    public function toParamsExclude($excludeColumns=[],$assoc=TRUE){
        $cols = $this::getColumns();
        $params = array();
        foreach ($cols as $column){
            if(!in_array($column,$excludeColumns)){
                if($assoc){
                    $params[":var_$column"] = $this->{$column};
                }
                else {
                    array_push($params,$this->{$column});
                }
            }
        }
        return $params;
    }

    public function toParamInclude($includeColumns=[],$assoc=TRUE){
        if(!count($includeColumns)){return $this->toParamsExclude();}
        $params = array();
        foreach ($includeColumns as $column){
            if($assoc)$params[":var_$column"] = $this->{$column};
            else array_push($params,$this->{$column});
        }
        return $params;
    }

    public static function initBy($query,$params){
        $db = new Db($query,$params,Db::getInstance());
        if ($db->runQuery() && $db->getStm()->rowCount()){
            $model = $db->getStm()->fetchObject(get_called_class());
        }else{
            $model = NULL;
        }
        $db->log_prepared();
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
        $query = Db::makeQuery("SELECT",[$table],$columns,$constraint,$options);
        $connection = new Db($query,null);
        $connection->log_prepared(UserSession::log());
        $stm = $connection->runQuery()?$connection->getStm():NULL;
        unset($connection);
        return $stm;
    }

    public function test(){print_r($this->{self::$tId});}

}