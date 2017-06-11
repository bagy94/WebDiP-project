<?php
namespace bagy94\model;

require_once "db/Db.php";

use bagy94\utility\db\Db;

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
    const REGEX_COLUMNS = "/^(t[A-Z]{1}[a-zA-z\_]+)$/";
    protected static $columns = [];
    /***
     * @var Db $connection
     */
    protected $connection;

    public function connect($query="",$params=[]){
        $this->connection = new Db($query,$params);
        return $this->connection->connect();
    }

    public function disconnect(){
        if(isset($this->connection)){
            $this->connection->disconnect();
        }
        $this->connection = NULL;
    }

    /**
     * @return Db
     */
    public function getConnection()
    {
        return $this->connection;
    }


    /**
     * @return array
     */
    protected function columns()
    {
        $vars = array_keys(get_class_vars(get_called_class()));
        return array_filter($vars,function($var){
            return preg_match(self::REGEX_COLUMNS,$var);
        });
    }
    public static function getColumns(){
        if(count(self::$columns))return self::$columns;
        $class = get_called_class();
        $vars = get_class_vars($class);
        foreach ($vars as $key=>$value){
            if (preg_match(self::REGEX_COLUMNS,$key)){
                array_push(self::$columns,$value);
            }
        }
        return self::$columns;
        /*$vars = array_keys(get_class_vars(get_called_class()));
        return array_filter($vars,function($var){
            return preg_match(self::REGEX_COLUMNS,$var);
        });*/
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

}