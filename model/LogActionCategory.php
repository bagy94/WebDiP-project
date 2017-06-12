<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 11.06.17.
 * Time: 19:01
 */

namespace bagy94\model;


class LogActionCategory extends Model
{

    public static $t = "sys_actions_category";
    public static $tId = "id";
    public static $tName = "name";


    private $id,$name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return LogActionCategory
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return LogActionCategory
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }






}