<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 31.05.17.
 * Time: 14:41
 */

namespace bagy94\utility;
use stdClass;

class JSONParser
{

    private $data;
    function __construct($JSONString,$assoc=FALSE)
    {
        $json = str_replace("'","\"",$JSONString);
        $this->data = json_decode($json,$assoc);
    }

    public function get($property)
    {
        if($this->data instanceof stdClass){
            return isset($this->data->$property)?$this->data->$property:NULL;
        }
        return is_array($this->data) && isset($this->data[$property])?$this->data[$property]:NULL;
    }
}