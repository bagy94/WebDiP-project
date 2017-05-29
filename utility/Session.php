<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 5.5.2017.
 * Time: 0:44
 */

namespace bagy94\utility;


class Session
{
    const DEFAULT_SESSION_NAME = "application_log_in_session";
    private $SESSION_NAME;

    /**
     * Session constructor.
     * @param null|string $name
     */
    function __construct($name=NULL)
    {
        $this->SESSION_NAME = isset($name) && is_string($name)?$name:self::DEFAULT_SESSION_NAME;
    }


    /**
     * Activate session.
     */
    public function startSession(){
        session_name($this->SESSION_NAME);

        if ($this->isActive()) {
            session_start();
            session_regenerate_id();
        }
    }
    function set($key,$val){
        $this->startSession();
        $_SESSION[$key] = $val;
    }
    static function get($key){
        return isset($_SESSION[$key])?$_SESSION[$key]:NULL;
    }
    public function destroy(){
        session_name(self::$SESSION_NAME);

        if ($this->isActive()) {
            session_unset();
            session_destroy();
        }
    }
    public static function isSetValue($key){
        return isset($_SESSION[$key]);
    }

    public function isActive(){
        session_name($this->SESSION_NAME);
        return session_status() === PHP_SESSION_ACTIVE;
    }

}