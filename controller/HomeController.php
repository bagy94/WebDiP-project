<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:36
 */

namespace bagy94\controller;

require_once "Controller.php";
require_once "model/ServiceCategory.php";

use bagy94\model\ServiceCategory;
use bagy94\utility\PageSettings;
use bagy94\utility\WebPage;
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
        $categoryList = [];
        $stm = ServiceCategory::getAll(array(ServiceCategory::$tId,ServiceCategory::$tName),"deleted=0");
        while (list($id,$name)=$stm->fetch()){
            $categoryList[$id]=$name;
        }
        $ps = new PageSettings();
        $page = new WebPage("view/index.tpl","Početna","index",$ps);
        $page->assign("items",$categoryList);
        $page->show();
    }

    /**
     * @inheritDoc
     */
    function actions()
    {
        return array();
    }

}