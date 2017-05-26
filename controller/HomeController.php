<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:36
 */

namespace bagy94\webdip\wellness\controller;

require_once "Controller.php";
require_once "model/ServiceCategory.php";

use bagy94\webdip\wellness\model\ServiceCategory;
use bagy94\webdip\wellness\model\User;

class HomeController extends Controller
{
    public static $CONTROLLER = "home";
    protected $actions = [];

    function error()
    {
        $error = "Nije moguce ucitati pocetnu stranicu";
        require_once("../view/error.php");
    }

    function index()
    {
        $categoryList = "";
        $stm = ServiceCategory::getAll(array(ServiceCategory::$tId,ServiceCategory::$tName),"deleted=0");
        while (list($id,$name)=$stm->fetch()){
            $categoryList .="<option value='$id'>$name</option>";
        }
        require_once("view/index_layout.php");
    }

    /**
     * @inheritDoc
     */
    function actions()
    {
        return array();
    }

}