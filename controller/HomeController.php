<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:36
 */

namespace bagy94\controller;

use bagy94\model\Log;
use bagy94\model\Service;
use bagy94\model\ServiceCategory;
use bagy94\utility\Router;
use bagy94\utility\UserSession;
use SimpleXMLElement;

class HomeController extends Controller
{
    const ACTION_VISIT_HOME = 2;
    const VISIT_DOC = 10;
    const VISIT_ABOUT = 11;
    const ACTION_SERVICE_LIST_OF_SERVICE = 17;

    const ARG_POST_SERVICE_CATEGORY_ID = "scid";
    const VIEW_VAR_ITEMS = "items";
    public static $KEY = "home";

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
        Log::write(self::ACTION_VISIT_HOME,"Pregled home/index");
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
        $scid = $this->filterPost(self::ARG_POST_SERVICE_CATEGORY_ID,NULL,FILTER_SANITIZE_NUMBER_INT);
        if(!$scid){
            $xml->addAttribute("success","0");
            $xml->addAttribute("message","Kategorija nije odabrana");
            return $this->render($xml,"XML");
        }
        $result = Service::getServiceFromReservationByCategory($scid,1);
        if($result->success){
            $arr = $result->getData();
            foreach ($arr as $service){
                $child = $xml->addChild("service",$service->getServiceId());
                $child->addAttribute("ime",$service->getName());
                $child->addAttribute("opis",$service->getDescription());
                $child->addAttribute("trajanje",$service->getDuration());
                $child->addAttribute("cijena",$service->getPrice());
            }
        }
        else{
            $xml->addAttribute("message","Nema rezervacija");
        }
        Log::write(self::ACTION_SERVICE_LIST_OF_SERVICE,"Pregled tri najrezerviranije usluge");
        return $this->render($xml,"XML");
    }

    /**
     * Documentation page.
     * @return \bagy94\utility\Response
     */
    function doc(){
        $this->pageAdapter->getSettings()->addAsset(Router::asset("era"),"era");
        $this->pageAdapter->setTitle("Dokumentacija");
        Log::write(self::VISIT_DOC,"Pregled dokumentacije");
        return $this->render($this->pageAdapter->getHTML(2));
    }

    /**
     * About page
     * @return \bagy94\utility\Response
     */
    function about(){
        $this->pageAdapter->getSettings()->addAsset(Router::asset("me","jpg"),"me");
        $this->pageAdapter->setTitle("O autoru");
        Log::write(self::VISIT_ABOUT,"Pregled stranice o autoru");
        return $this->render($this->pageAdapter->getHTML(1));
    }

    /**
     * Returns array of possible actions
     * @return callable[]
     */
    function actions()
    {
        return ["index","services","doc","about"];
    }

    /**
     * Returns array of templates in controller
     * @return string[]
     */
    function templates()
    {
        return ["view/index.tpl","view/about.tpl","view/doc.tpl"];
    }
}