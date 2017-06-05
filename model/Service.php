<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 02.06.17.
 * Time: 00:19
 */

namespace bagy94\model;
require_once "Model.php";

use bagy94\utility\db\Db;
use bagy94\utility\db\DbResult;

class Service extends Model
{
    const TOP_3_RESERVED = "SELECT COUNT( * ) , s.service_id, s.name, s.duration, s.description,s.price
                                FROM service s
                                INNER JOIN sc_assignments sca ON s.deleted=0 
                                  AND s.assignment_id = sca.assignment_id
                                  AND sca.category_id = :varCategoryId
                                INNER JOIN reservations r ON r.service_id = s.service_id
                                  AND r.state_id =:varReservationState
                                GROUP BY s.service_id
                                ORDER BY 1 DESC";

    public static $t = "service";
    public static $tId = "service_id";
    public static $tAssignmentId = "assignment_id";
    public static $tName = "name";
    public static $tDuration = "duration";
    public static $tDescription = "description";


    protected $service_id,$assignment_id,$name,$duration,$price,$description;



    public function __construct($id = NULL, $data = array())
    {
        parent::__construct($id, $data);
    }

    /**
     * @return mixed
     */
    public function getServiceId()
    {
        return $this->service_id;
    }

    /**
     * @param mixed $service_id
     * @return Service
     */
    public function setServiceId($service_id)
    {
        $this->service_id = $service_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssignmentId()
    {
        return $this->assignment_id;
    }

    /**
     * @param mixed $assignment_id
     * @return Service
     */
    public function setAssignmentId($assignment_id)
    {
        $this->assignment_id = $assignment_id;
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
     * @return Service
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     * @return Service
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return Service
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Service
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


    /**
     * @param int $categoryId
     * @param int $reservationState
     * @param int $limit
     * @return DbResult
     */
    public static function getServiceFromReservationByCategory($categoryId, $reservationState, $limit=3){
        $db = new Db(self::TOP_3_RESERVED." LIMIT $limit",[":varCategoryId"=>$categoryId,":varReservationState"=>$reservationState]);
        $result = new DbResult($db->connect()->prepare()->runQuery(),array());
        if($result->success){
            while (($obj = $db->getStm()->fetchObject(__CLASS__))){
                //print_r($obj);
                $result->appendRow($obj);
            }
        }
        $db->disconnect();
        return $result;
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