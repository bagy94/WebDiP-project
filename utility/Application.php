<?php

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 03.06.17.
 * Time: 10:44
 */
namespace bagy94\utility;
use bagy94\model\Configuration;

class Application
{

    const TIMESTAMP_FORMAT = "Y-m-d H:i:s";
    const DATE_FORMAT = "Y-m-d";


    /**
     * Function which returns current application timestamp in MYSQL Timestamp format
     * @return string
     */
    public static function appTimeStamp()
    {
        $interval = Configuration::Instance()->interval();
        //print("<br>INTERVAL $interval<br>");
        return date(self::TIMESTAMP_FORMAT,strtotime("$interval hours"));
    }

    public static function dateFormat($dateToChange){
        return date(self::DATE_FORMAT,strtotime($dateToChange));
    }

    public static function toTimeFormat($timestamp)
    {
        return date(self::TIMESTAMP_FORMAT,$timestamp);
    }
}