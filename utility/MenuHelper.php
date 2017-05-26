<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 9.5.2017.
 * Time: 18:48
 */

namespace bagy94\webdip\wellness\utility;
require_once "Session.php";
require_once "model/User.php";

use bagy94\webdip\wellness\model\User;
use bagy94\webdip\wellness\utility\Session;

class MenuHelper
{
    protected static $session;

    public static function Instance(){
        if (!isset(self::$session)){
            self::$session = new Session();
        }
        return self::$session;
    }

    function __clone()
    {
        // TODO: Implement __clone() method.
    }
    private function __construct()
    {
    }

    static function show(){

    }
}