<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 28.05.17.
 * Time: 16:29
 */

namespace bagy94\utility;
use bagy94\model\Configuration;

require_once "Session.php";

class UserSession
{
    CONST ADMINISTRATOR = "1";
    const MODERATOR ="2";
    const REGULAR = "3";
    const KEY_USER_ID = "user_id";
    const KEY_USER_TYPE = "type_id";
    const KEY_USERNAME = "username";
    const SESSION_DURATION = "session_duration";
    const LOG_IN_CODE = "login_code";

    const SESSION_START = "start";
    const LAST_ACTIVITY = "last_activity";


    const COOKIE_USERNAME = "user";
    /***
     * @var Session $session
     */
    private static $session;
    /***
     * @var Configuration $configuration
     */
    private static $configuration;

    private function __construct()
    {

    }

    public static function config(){
        if (!isset(self::$configuration)){
            self::$configuration = new Configuration();
        }
        return self::$configuration;
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
        if (!self::$session->isActive()){
            self::$session->resume();
            //print "Starting session:";
        }
        return self::$session;
    }

    function __clone()
    {
        // TODO: Implement __clone() method.
    }


    /**
     * Start user session.
     * Set user id, username and user type in session.
     * Set username in cookie.
     * @param int $userid
     * @param int $typeid
     * @param string $username
     * @return bool
     */
    public static function start($userid, $typeid, $username,$cookie=TRUE)
    {
        self::session()->startSession(self::config()->sessionRealTimeDuration());
        self::session()->set(self::KEY_USER_ID,$userid);
        self::session()->set(self::KEY_USER_TYPE,$typeid);
        self::session()->set(self::KEY_USERNAME,$username);
        self::session()->set(self::LAST_ACTIVITY,
            self::config()->currentTimestamp())
        ;
        self::session()->set(self::SESSION_START,self::config()->currentTimestamp());
        if($cookie){
            //echo "Setting cookie";
            setcookie(self::COOKIE_USERNAME,$username,self::$configuration->getCookieEndTime(),"/");
        }else{
            setcookie(self::COOKIE_USERNAME,"",time()-(3600*5),"/");
        }
        //var_dump($_SESSION);
        return $cookie;
    }

    public static function stop()
    {
        self::session()->destroy();
        return setcookie(self::COOKIE_USERNAME,"",time()-3600);
    }




    public static function coookie($name=self::COOKIE_USERNAME)
    {
        //print "COOKIE_MASTER: ".filter_input(INPUT_COOKIE,$name,FILTER_SANITIZE_STRING);
        return isset($_COOKIE[$name])?filter_input(INPUT_COOKIE,$name,FILTER_SANITIZE_STRING):NULL;
    }
    /**
     * Checks if user id is in session variable
     * @return bool
     */
    public static function isLogIn()
    {
        self::session()->resume();
        $last = (int)Session::get(self::LAST_ACTIVITY);
        $time = (int)Configuration::Instance()->currentTimestamp();

        if(($last+Configuration::Instance()->sessionDuration(Configuration::TIME_TYPE_MINUTES))>$time){
            self::session()->set(self::LAST_ACTIVITY,$time);
        }else{
            self::session()->free();
            return FALSE;
        }
        //$last + duration > $current = active

        return self::session()->isSetValue(self::KEY_USER_ID)?TRUE:FALSE;
    }

    /**
     * Checks is active user administrator
     * @return bool
     */
    public static function isAdmin()
    {
        self::session()->resume();
        return Session::get(self::KEY_USER_TYPE) == self::ADMINISTRATOR;
    }

    /**
     * Check is active user moderator
     * @return bool
     */
    public static function isModerator()
    {
        self::session()->resume();
        return self::get(self::KEY_USER_TYPE) == self::MODERATOR;

    }

    /**
     * Get user id from session
     * @return string|null
     */
    public static function getUserId()
    {
        self::session()->resume();
        return Session::get(self::KEY_USER_ID);
    }

    /**
     * Get user name from session
     * @return string|null
     */
    public static function getUserName(){
        self::session()->resume();
        return Session::get(self::KEY_USERNAME);
    }

    /**
     * Get user type from session
     * @return string|null
     */
    public static function getUserType()
    {
        self::session()->resume();
        return Session::get(self::KEY_USER_TYPE);
    }

    public static function log()
    {
        return self::getUserId();
    }

    public static function isSessionEnded(){
        return Session::get(self::LAST_ACTIVITY) > time();
    }
}