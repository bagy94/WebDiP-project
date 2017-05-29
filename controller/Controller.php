<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:16
 */


namespace bagy94\controller;
require_once "utility/WebPage.php";
use bagy94\utility\WebPage;

abstract  class Controller
{
    /*** array of base actions which must be implemented***/
    public static $baseActions = ["index","error"];
    /**
     * Actions array.
     * @var string[] $actions
     **/
    protected $actions;

    /***
     * Controller name
     * Must be inherited
     ***/
    public static $CONTROLLER;

    /***
     * Instance of smarty.
     * Access it over getter
     * @var Smarty $smarty
     */
    private static $smarty;


    /**
     * Controller constructor.
     * Initialize array of actions based on actions() method
     **/
    function __construct()
    {
        $this->actions = $this->actions();
    }


    /*** Function to show error view*/
    function error(){
        $error = "Page not found";
        require_once "../view/error.php";
    }

    /**
     * Indicates if controller contains action in param
     * @param $action
     * @return bool
     **/
    function hasAction($action){
        return (is_array($this->actions) && in_array($action,$this->actions)) || in_array($action,self::$baseActions);
    }




    /**
     * Function used as base action of controller
     * @return void
     **/
    abstract function index();

    /**
     * Function returns array of controller actions
     * @return string[]
     **/
    abstract function actions();
}