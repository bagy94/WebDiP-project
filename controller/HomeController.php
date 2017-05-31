<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:36
 */

namespace bagy94\controller;
require_once "model/ServiceCategory.php";

use bagy94\model\ServiceCategory;
use bagy94\utility\PageSettings;
use bagy94\utility\WebPage;
class HomeController extends Controller
{
    const VIEW_VAR_ITEMS = "items";
    public static $KEY = "home";
    protected $actions = ["index"];
    protected $templates = ["view/index.tpl"];

    function __construct()
    {
        parent::__construct("Početna","pocetna index prva stranica");
    }

    function index()
    {
        $categoryList = [];
        $stm = ServiceCategory::getAll(array(ServiceCategory::$tId,ServiceCategory::$tName),"deleted=0");
        while (list($id,$name)=$stm->fetch()){
            $categoryList[$id]=$name;
        }
        $this->pageAdapter->assign(self::VIEW_VAR_ITEMS,$categoryList);
        $this->pageAdapter->show();
    }
}