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
use SimpleXMLElement;

class HomeController extends Controller
{
    const ARG_POST_SERVICE_CATEGORY_ID = "scid";
    const VIEW_VAR_ITEMS = "items";
    public static $KEY = "home";
    protected $actions = ["index","services"];
    protected $templates = ["view/index.tpl"];

    function __construct()
    {
        parent::__construct("Početna","pocetna index prva stranica");
    }

    /**
     * Index page of home controller.
     * Returns HTML.
     * @return \bagy94\utility\Response
     */
    function index()
    {
        $categoryList = [];
        $stm = ServiceCategory::getAll(array(ServiceCategory::$tId,ServiceCategory::$tName),"deleted=0");
        while (list($id,$name)=$stm->fetch()){
            $categoryList[$id]=$name;
        }
        $this->pageAdapter->assign(self::VIEW_VAR_ITEMS,$categoryList);
        $this->pageAdapter->getSettings()->addJsLocal("index");
        $this->pageAdapter->getSettings()->addCssLocal("index");
        return $this->render($this->pageAdapter->getHTML());
    }
    //isset($_GET[self::ARG_POST_SERVICE_CATEGORY_ID]
    /**
     * Service wich returns top 3 service in category
     * Returns XML
     * @return \bagy94\utility\Response
     */
    function services(){
        $xml = new SimpleXMLElement("<xml/>");
        $scid = filter_input(INPUT_GET,self::ARG_POST_SERVICE_CATEGORY_ID,FILTER_SANITIZE_NUMBER_INT);
        if(!$scid){
            $xml->addAttribute("success","0");
            $xml->addAttribute("message","Kategorija nije odabrana");
            return $this->render($xml,"XML");
        }

    }
}