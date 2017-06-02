<?php
namespace bagy94\model;

require_once "db/Db.php";

use bagy94\utility\db\Db as DbConnection;
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 2.4.2017.
 * Time: 19:51
 */
/**/

abstract class MetaModel
{
    /*
     * Regex patterns
     * REGEX_COLUMNS-used for getting column name variables
     * REGEX_TAGS-cover all tags on object
    */
    const REGEX_COLUMNS = "/^(t[A-Z]{1}[a-zA-z\_]+|t{1})$/";
    /***
     * @var DbConnection $connection
     */
    protected $connection;

    protected $created_at,$deleted;



    public function connect(){
        $this->connection = DbConnection::getInstance();
        $this->connection->connect();
    }

    public function disconnect(){
        if(isset($this->connection)){
            $this->connection->disconnect();
        }
        $this->connection = NULL;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     * @return MetaModel
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param mixed $deleted
     * @return MetaModel
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return DbConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }




    /**
     * @return array
     */
    public function columns()
    {
        return array_keys(array_filter(get_class_vars(get_called_class()), function($k){
            return preg_match(self::REGEX_COLUMNS,$k);
        },ARRAY_FILTER_USE_KEY));
    }

    public static function filterArray($array,$handler){
        $fileterdArray = $array();
        foreach ($array as $key=>$value){
            $foo = call_user_func($handler,$key,$value);
            if(isset($foo) && $foo){
                $fileterdArray[$key] = $value;
            }
        }
        return $fileterdArray;
    }


    abstract function getColumns();
}