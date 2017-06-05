<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 2.4.2017.
 * Time: 19:23
 */

namespace bagy94\utility\db;
require_once "DbResult.php";

use bagy94\utility\db\DbResult;
use bagy94\utility\UserSession;
use \PDO as PDO;


class Db
{
    private static $WRITE_LOG = "INSERT INTO `log` VALUES (DEFAULT,?,?,?,NOW(),0)";
    private static $GET_LOGS = "SELECT * FROM `log` ";


    private static $UPDATE = "UPDATE %s SET %s WHERE %s";
    private static $INSERT = "INSERT INTO %s VALUES (%s)";
    private static $SELECT = "SELECT %s FROM %s";
    private static $SELECT_WHERE = "SELECT %s FROM %s WHERE %s";


    private static $stmWriteLog = NULL;

    const dbFile = "db_data.ini";
    const dbQueryPrepPrefix = ":var";

    protected $query;
    protected $queryParams = NULL;
    /*** @var DbResult $dbResult */
    protected $dbResult = NULL;
    /*** @var \PDO $connection */
    protected $connection;
    /*** @var \PDOStatement $stm */
    protected $stm;

    protected $constraints=[];
    protected $columns = [];

    public function __construct($query="",$params=[],$pdo=null)
    {
        $this->connection = $pdo;
        $this->query = $query;
        $this->queryParams = $params;
    }

    /**
     *set new pdo instance for database in db_data.ini file
     * @return Db
     */
    public function connect(){
        $this->connection = self::getInstance();
        return $this;
    }


    public function startTransaction(){
        if(!isset($this->connection)){
            $this->connect();
        }
        $this->connection->beginTransaction();
    }

    public function closeTransaction()
    {
        $this->connection->commit();
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Prepare $stm
     * @return Db
     */
    public function prepare(){
        if(!is_string($this->query))return NULL;
        if(is_null($this->query))return NULL;
        if(!isset($this->connection)){
            $this->connection = self::getInstance();
        }

        $this->stm = $this->connection->prepare($this->query);
        return $this;
    }


    /**
     * Execute $stm
     * @return bool
     */
    public function query(){
        if(!isset($this->stm))$this->dbResult = new DbResult(0,NULL,"Query not prepared");
        if($this->stm->execute($this->queryParams)){
            if (preg_match("/^(insert|update|delete)/i", strtolower($this->stm->queryString))) {
                $this->dbResult = new DbResult(1,$this->stm->rowCount());
            } else if ($this->stm->rowCount() == 1) {

                $this->dbResult = new DbResult(1,array($this->stm->fetch(PDO::FETCH_ASSOC)));
            } else if ($this->stm->rowCount() > 1) {
                $this->dbResult = new DbResult(1,$this->stm->fetchAll(PDO::FETCH_ASSOC));
            } else {
                $this->dbResult = new DbResult(1,NULL,"no data found");
            }
        }else{
            $this->dbResult = new DbResult(0,NULL,"Query executed unsuccesfull");
        }
        //echo "<br>QueryResult";
        //print_r($this->dbResult);
        $this->log_prepared(UserSession::log());
        return $this->dbResult->success;
    }

    /**
     * @return bool
     */
    public function runQuery(){
        if(!isset($this->stm) || !is_a($this->stm,"PDOStatement")){
            $this->prepare();
        }
        $this->log_prepared(UserSession::log());
        return $this->stm->execute($this->queryParams);
    }

    /**
     * @return bool
     */
    public function isSucces()
    {
        return isset($this->dbResult) && $this->dbResult->success;
    }


    /**
     * @return mixed|null
     */
    public function data(){
        return $this->dbResult->getData();
    }

    /**
     *Unset connection
     */
    public function disconnect(){
        if(isset($this->stm)){
            $this->stm->closeCursor();
        }
        if($this->connection->inTransaction()){

        }
        $this->stm = NULL;
        $this->connection= NULL;
    }
    /**
     * ret new pdo instance for database in db_data.ini file
     * @return PDO
     * @throws Exception
     */
    public static function getInstance() {
        $file = parse_ini_file(self::dbFile,TRUE);
        if(!$file)throw new Exception ("ErrorController: Cant open db_ini file");
        $con = new PDO($file["database"]["dsn"],$file["database"]["user"],$file["database"]["pass"]);
        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        unset($file);
        return $con;
    }

    /**
     * @param string $query
     * @param PDO $conn
     * @return \PDOStatement|int
     */
    public static function execute($query,$conn = NULL){
        if(!is_string($query))return NULL;
        if(is_null($conn)){
            $conn = self::getInstance();
        }
        if (preg_match("/^(insert|update|delete|create)/i", strtolower($query))) {
            return $conn->exec($query);
        }
        return $conn->query($query);
    }

    /*** @return string */
    public function getQuery()
    {
        return $this->query;
    }
    /*** @param string $query */
    public function setQuery($query)
    {
        $this->query = $query;
    }
    /*** @return mixed */
    public function getQueryParams()
    {
        return $this->queryParams;
    }
    /*** @param array $queryParams */
    public function setQueryParams($queryParams)
    {
        $this->queryParams = $queryParams;
    }

    public function makeUpdate($table,$columns=[],$constraints=[],$constraintGlue = " AND ")
    {
        $this->prepareColumns($columns)->prepareConstraints($constraints);
        $this->setQuery(sprintf(self::$UPDATE,
            $table,
            implode(",",$this->columns),
            implode($constraintGlue,$this->constraints)
        ));
        return $this;
    }

    public function prepareConstraints($constraints=[]){
        foreach ($constraints as $column=>$value){
            $param = ":var_$column";
            $foo = "`$column`= $param";
            array_push($this->constraints,$foo);
            $this->queryParams[$param] = $value;
        }
        return $this;
    }

    public function prepareColumns($columns=[])
    {
        foreach ($columns as $column=>$columnValue){
            $param = ":var_$column";
            $foo = "`$column`= $param";
            array_push($this->columns,$foo);
            $this->queryParams[$param]= $columnValue;
        }
        return $this;
    }


    public function lastId()
    {
        if(isset($this->connection)){
            return $this->connection->lastInsertId();
        }
        return "-1";
    }


    public static function sort($column){
        return is_null($column)?"":" ORDER BY {$column}";
    }
    public static function limit($numOfRows){
        return is_numeric($numOfRows)?" LIMIT {$numOfRows}":"";
    }
    public static function offset($startPosition){
        return is_numeric($startPosition)?" OFFSET {$startPosition}":"";
    }

    public function getStm()
    {
        return $this->stm;
    }

    public function addParam($param)
    {
        if(!isset($this->queryParams)){
            $this->queryParams = array($param);
        }else array_push($this->queryParams,$param);
    }

    public static function isAssocArray($array = array()){
        if(count($array)===0){
            return FALSE;
        }
        return is_string(key($array));
    }

    public static function isFunction($value){
        return preg_match("/^[A-Za-z_]+\([A-Za-z0-9,]*\){1}$/", $value);
    }




    private static function stmLog($inTransacionPDO=NULL)
    {
        if(!isset(self::$stmWriteLog)){
            $connection = Db::getInstance();
            self::$stmWriteLog = $connection->prepare(self::$WRITE_LOG);
        }
        return self::$stmWriteLog;
    }

    public function writeLog($keyword,$content,$user)
    {
        self::stmLog()->execute([$keyword,$content,$user]);
        return $this;
    }


    public function log_prepared(){
        $query = str_replace("'","",$this->query);
        $params = is_array($this->queryParams)?":[".implode(";",$this->queryParams)."]": "";
        return $this->writeLog("db",$query.$params,UserSession::log());
    }









    public static function updateQuery($table,$data,$constraint){
        $querry = "UPDATE ".$table." SET ";
        $k = count($data)-1;
        foreach ($data as $key => $value) {
            if(!is_null($key)){
                $foo = self::isFunction($value)?"{$value}":"'{$value}'";
                $querry .="`{$key}`={$foo}";
            }
            if($k--){
                $querry .=", ";
            }
        }
        return $querry .= " WHERE ".$constraint;
    }
    public static function insertQuery($table,$data=array()){
        $insert = "INSERT INTO `{$table}`";
        $columns = array();
        $values = array();
        foreach ($data as $key => $value) {
            array_push($columns,"`{$key}`");

            $foo = self::isFunction($value)?$value:"'{$value}'";
            //print("<br>{$foo}");
            if(is_null($value)){
                $foo = "NULL";
            }
            array_push($values,"{$foo}");
        }
        $insert .= "(".implode(",", $columns).")";
        $insert .= "VALUES (".implode(",", $values).")";
        unset($columns);
        unset($values);
        return $insert;

    }
    public static function selectQuery($tables=array(),$columns=array(),$constraint = NULL,$options=NULL) {
        $col = count($columns) === 0 || is_null($columns)?"*":implode(",",$columns);
        $tab = is_array($tables)?implode(",", $tables):$tables;
        $cons = "";
        if(!is_null($constraint)){
            if(is_array($constraint)){
                if(count($constraint)){
                    $foo = implode(" and ", $constraint);
                    $cons =" WHERE {$foo}";
                }
            }  else {
                $cons =" WHERE {$constraint}";
            }
        }
        if(is_null($options)){
            $options="";
        }
        $select = "SELECT {$col} FROM {$tab} {$cons} {$options}";
        return $select;
    }
    public static function makeQuery($keyword,$table=array(),$data = array(),$condition=NULL,$options = NULL){
        $querry="";
        switch (strtoupper($keyword)){
            case "SELECT":$querry = self::selectQuery($table,$data,$condition, $options); break;
            case "INSERT":$querry = self::insertQuery(is_array($table)?$table[0]:$table, $data);break;
            case "UPDATE":$querry = self::updateQuery(is_array($table)?$table[0]:$table, $data, $condition);break;
            default :

        }
        //echo "<br>{$querry}";
        return $querry;
    }

    public function getLogs($options=""){
        if(!isset($this->connection)){
            $this->connect()->setQuery(self::$GET_LOGS.$options);
        }
        return $this->prepare()->getStm()->execute();
    }

}