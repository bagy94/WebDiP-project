<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 05.06.17.
 * Time: 16:00
 */

namespace bagy94\model;
use bagy94\utility\db\Db;

/***
 * Class SystemActions
 * Singleton
 * @package bagy94\model
 */
class SystemActions extends MetaModel
{
    const QUERY_ALL = "SELECT * FROM  `sys_actions`";
    private static $_instance = NULL;
    private $db = NULL;

    private $point_action_id,$category_id,$name,$action_definition,$value,$active;


    private function __construct()
    {
        $this->db = new Db();
    }

    /**
     * @return SystemActions
     */
    public static function Instance(){
        if (!isset(self::$_instance)){
            self::$_instance = new SystemActions();
        }
        return self::$_instance;
    }

    public function get(){

    }

    /**
     *
     */
    private function __clone(){}

    /**
     * @return mixed
     */
    public function getPointActionId()
    {
        return $this->point_action_id;
    }

    /**
     * @param mixed $point_action_id
     */
    public function setPointActionId($point_action_id)
    {
        $this->point_action_id = $point_action_id;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getActionDefinition()
    {
        return $this->action_definition;
    }

    /**
     * @param mixed $action_definition
     */
    public function setActionDefinition($action_definition)
    {
        $this->action_definition = $action_definition;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }














}