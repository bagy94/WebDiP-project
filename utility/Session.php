<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 5.5.2017.
 * Time: 0:44
 */

namespace bagy94\webdip\wellness\utility;


class Session
{
    protected static $SESSION_NAME = "application_log_in_session";

    const KEY_USER = "userWellnessApp";


    public function startSession(){
        session_name(self::$SESSION_NAME);

        if (session_id() == "") {
            session_start();
            session_regenerate_id();
        }
    }
    function set($key,$val){
        $this->startSession();
        $_SESSION[$key] = $val;
    }
    function get($key){
        return isset($_SESSION[$key])?$_SESSION[$key]:NULL;
    }
    public function destroy(){
        session_name(self::$SESSION_NAME);

        if (session_id() == "") {
            session_unset();
            session_destroy();
        }
    }
    public function isSetValue($key){
        return isset($_SESSION[$key]);
    }

}