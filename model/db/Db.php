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
    const DB_ACTION_QUERY = 16;

    private static $WRITE_LOG = "INSERT INTO `sys_log` VALUES (DEFAULT,?,?,?,MY_SYSTEM_TIME(),0)";
    private static $GET_LOGS = "SELECT * FROM `log` ";


    private static $UPDATE = "UPDATE %s SET %s WHERE %s";
    private static $INSERT = "INSERT INTO %s VALUES (%s)";
    private static $INSERT_COLUMN = "INSERT INTO %s(%s) VALUES (%s)";
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
     * Return new pdo instance for database in db_data.ini file
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






    // Getters and Setters
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

    //Make query methods

    /**
     * Create update query and saves it in query variable.
     * Prepare columns to be set and constraints to look after in update.
     * Constraint glue is default on AND
     * @param $table
     * @param array $columns
     * @param array $constraints
     * @param string $constraintGlue
     * @return $this
     */
    public function makeUpdate($table, $columns=[], $constraints=[], $constraintGlue = " AND ")
    {
        $this->prepareColumns($columns)->prepareConstraints($constraints);
        $this->setQuery(sprintf(self::$UPDATE,
            $table,
            implode(",",$this->columns),
            implode($constraintGlue,$this->constraints)
        ));
        //print $this->query;
        return $this;
    }


    public function makeInsert($table,$columns=[]){
        if (!count($columns))return NULL;
        $foo = array();
        foreach ($columns as $column){
            array_push($this->columns,$column);
            $this->queryParams[":var_$column"]= NULL;
        }
        $this->setQuery(sprintf(self::$INSERT_COLUMN,
            $table,
            implode(",",$this->columns),
            implode(",",array_keys($this->queryParams))
        ));

        return $this;
    }

   /* public function makeSelect($tables=[],$columns=[],$constraint=[],$options = "")
    {
        $tab = implode(",",$tables);
        $cols = isset($columns) && count($columns)?implode(",",$columns):"*";


    }*/

   public function makeSelect($tables=array(),$columns=array(),$constraints=array(),$constraintGlue= " AND ", $options =NULL){
       $this->query = "SELECT ";
       if (isset($columns) && count($columns)){
           $this->query .= implode(",",$columns)." FROM ";
       }else{
           $this->query .= "* FROM ";
       }
       if(is_array()){
           $this->query .= implode(",",$tables);
       }else{
           $this->query .= $tables;
       }
       if(count($constraints)){
           $this->query .= " WHERE ".implode($constraintGlue,$constraints);
       }
       if(!$options){
           $options = "";
       }
        $this->query .= $options;
       return $this->query;
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

    public function where($constraints=[],$glue= "AND"){
        if (isset($constraints) && is_array($constraints)){
            $last = end($constraints);
            reset($constraints);
            foreach ($constraints as $constraint=>$value){
                $param = ":var_$constraint";
                $this->queryParams[$param]=$value;
                $this->query .= "`$constraint`= $param";
                if($last !== $value)$this->query .= " $glue ";
            }
        }
    }

    public function like($columns=[],$glue="OR"){
        $last = end($columns);
        reset($columns);
        foreach ($columns as $column=>$value){
            $param = ":var_$column";
            $this->queryParams[$param] = "%$value%";
            $this->query .= "`$column` LIKE ($param)";
            if($value !== $last)$this->query .=" $glue ";
        }
        return $this;
    }
    public function limit($start=0,$stop=50){
        $this->query .= " LIMIT $start,$stop ";
        return $this;
    }
    public function sort($column = 1,$desc=FALSE){
        $this->query .= " ORDER BY $column";
        if($desc){
            $this->query .= " DESC";
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

    public function writeLog($actionId,$userID,$content)
    {
        self::stmLog()->execute([$actionId,$userID,$content]);
        return $this;
    }


    public function log_prepared(){
        $query = str_replace("'","",$this->query);
        $params = is_array($this->queryParams)?":[".implode(";",$this->queryParams)."]": "";
        return $this->writeLog(self::DB_ACTION_QUERY,UserSession::log(),$query.$params);
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