<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 28.05.17.
 * Time: 16:29
 */

namespace bagy94\utility;
require_once "Session.php";

class UserSession
{
    CONST ADMINISTRATOR = "1";
    const MODERATOR ="2";
    const REGULAR = "3";
    const KEY_USER_ID = "user_id";
    const KEY_USER_TYPE = "type_id";
    const KEY_USERNAME = "username";
    /***
     * @var Session $session
     */
    private static $session;

    private function __construct()
    {

    }

    /**
     * Get instance of session.
     * It will create only one object
     * @return Session
     */
    private static function session(){
        if(!isset(self::$session)){
            self::$session = new Session();
        }
        return self::$session;
    }

    function __clone()
    {
        // TODO: Implement __clone() method.
    }


    public static function start($userid,$typeid,$username)
    {
        self::session()->startSession();
        self::session()->set(self::KEY_USER_ID,$userid);
        self::session()->set(self::KEY_USER_TYPE,$typeid);
        self::session()->set(self::KEY_USERNAME,$username);
    }
    /**
     * Checks if user id is in session variable
     * @return bool
     */
    public static function isLogIn()
    {
        return self::session()->isSetValue(self::KEY_USER_ID)?TRUE:FALSE;
    }

    /**
     * Checks is active user administrator
     * @return bool
     */
    public static function isAdmin()
    {
        return Session::get(self::KEY_USER_TYPE) == self::ADMINISTRATOR;
    }

    /**
     * Check is active user moderator
     * @return bool
     */
    public static function isModerator()
    {
        return self::get(self::KEY_USER_TYPE) == self::MODERATOR;

    }

    /**
     * Get user id from session
     * @return string|null
     */
    public static function getUserId()
    {
        return Session::get(self::KEY_USER_ID);
    }

    /**
     * Get user name from session
     * @return string|null
     */
    public static function getUserName(){
        return Session::get(self::KEY_USERNAME);
    }

    /**
     * Get user type from session
     * @return string|null
     */
    public static function getUserType()
    {
        return Session::get(self::KEY_USER_TYPE);
    }

    public static function log()
    {
        return self::isLogIn()?self::getUserName():$_SERVER["REMOTE_ADDR"];
    }

}