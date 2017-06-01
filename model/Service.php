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
    const TOP_3_RESERVED = "SELECT COUNT( * ) , s.service_id, s.name, s.duration, s.description
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


    public function __construct($id = NULL, $data = array())
    {
        parent::__construct($id, $data);
    }


    function getColumns()
    {
        // TODO: Implement getColumns() method.
    }


    /**
     * @param int $categoryId
     * @param int $reservationState
     * @param int $limit
     * @return Service[]
     */
    public static function getServiceFromReservationByCategory($categoryId, $reservationState, $limit=3){
        $db = new Db();
        $db->connect();
        $db->setQuery(self::TOP_3_RESERVED." LIMIT $limit");
        $db->prepare();
        $db->stm->execute([":varCategoryId"=>$categoryId,":varReservationState"=>$reservationState]);
        $result = new DbResult($db->stm->rowCount(),[]);
        while (list($num,$id,$name,$duration,$description) = $db->stm->fetch()){
            $service = new \stdClass();
            $service->sifra = $id;
            $service->ime = $name;
            $service->trajanje = $duration;
            $service->opis = $description;
            $result->appendRow($service);
        }
        $db->disconnect();
        return $result;
    }
}