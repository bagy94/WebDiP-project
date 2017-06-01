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

abstract class MetaModel extends DbConnection
{
    /*
     * Regex patterns
     * REGEX_COLUMNS-used for getting column name variables
     * REGEX_TAGS-cover all tags on object
    */
    const REGEX_COLUMNS = "/^(t[A-Z]{1}[a-zA-z\_]+|t{1})$/";

    protected $data;

    /*** @return mixed */
    public function get($column)
    {
        return is_array($this->data) && array_key_exists($column,$this->data)?$this->data[$column]:"NULL";
    }

    /**
     * @param string $col
     * @param mixed $data
     * @return MetaModel
     */
    public function set($col,$data)
    {
        $this->data[$col] = $data;
        return $this;
    }
    public function getAllValues(){
        return $this->data;
    }
    public function initEmptyDataArray(){
        $cols = $this->columns();
        $arr = array();
        foreach ($cols as $col){
            $arr[$cols] = null;
        }
        $this->data = $arr;
        return $this;
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
    public function values(){
        return $this->data;
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