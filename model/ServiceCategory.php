<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 9.5.2017.
 * Time: 17:50
 */

namespace bagy94\model;
require_once "Model.php";

class ServiceCategory extends Model
{


    public static $t = "service_categorys";
    public static $tId = "category_id";

    public static $tName = "name";

    private $category_id,$name;

    function tags($columns = NULL)
    {
        // TODO: Implement tags() method.
    }

    /*
     * @return string[]
     */
    function getColumns()
    {
        return [self::$tName];
    }

    function save($columns = array())
    {
        // TODO: Implement save() method.
    }

    function init($constraint = NULL)
    {
        // TODO: Implement init() method.
    }
}