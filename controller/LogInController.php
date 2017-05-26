<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:57
 */

namespace bagy94\webdip\wellness\controller;
require_once "Controller.php";

class LogInController extends Controller
{
    public static $CONTROLLER = "login";

    function error()
    {
        // TODO: Implement error() method.
    }

    function index()
    {
       $form = new BaseLogInForm();

       require_once "view/login_layout.php";

    }

    /**
     * Function returns array of controller actions
     * @return string[]
     **/
    function actions()
    {
        return ["submit"];
    }
}