<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 26.05.17.
 * Time: 16:50
 */

namespace bagy94\utility;
require_once "external_libs/smarty/libs/Smarty.class.php";
use Smarty;


class WebPage
{
    const HEADER_PATH = "view/sections/_header.tpl";

    const VAR_TITLE = "title";
    const VAR_KEYWORDS = "keyword";
    const ARR_MENU = "menu";

    /***
     * Static instance of Smarty.
     * @var Smarty
     */
    private static $smarty;
    /***
     * Template path.
     * @var string
     */
    private $temp;
    /***
     * Page title.
     * @var string
     */
    private $title;
    /***
     * @var null|string
     */
    private $keywords;
    /***
     * @var PageSettings
     */
    private $settings;

    /**
     * WebPage constructor.
     * @param string $temp
     * @param string $title
     * @param string $keywords
     * @param PageSettings $settings
     */
    public function __construct($temp, $title, $keywords=NULL,$settings=NULL)
    {
        $this->temp = $temp;
        $this->title = $title;
        $this->keywords = $keywords;
        $this->settings = is_null($settings)?new PageSettings():$settings;
    }


    /**
     * Assign value to template variable.
     * @param string $var
     * @param mixed $val
     */
    function assign($var, $val){
        self::smarty()->assign($var,$val);
    }

    /**
     * Assign object to template variable.
     * @param string $var
     * @param object $obj
     */
    function assignObj($var, $obj){
        self::smarty()->registerObject("menu",$this->settings);
    }


    /**
     * Returns static instance of smarty
     * @return Smarty
     */
    public static function smarty()
    {
        if (!isset(self::$smarty)){
            self::$smarty = new Smarty();
        }
        return self::$smarty;
    }

    /**
     * Initialize header and menu.
     */
    public function init(){
        $this->assign(self::VAR_TITLE,$this->title);
        $this->assign(self::VAR_KEYWORDS,isset($this->keywords)?$this->keywords:"");
        if(!$this->settings->isCreatedMenu()){
            $this->settings->createMenu();
        }
        $this->assign(self::ARR_MENU,$this->settings->menu);
        self::smarty()->display(self::HEADER_PATH);
    }


    /***Display template specific for page.*/
    function displayContent()
    {
        self::smarty()->display($this->temp);
        //self::smarty()->display(self::FOOTER_PATH);
    }

    public function show()
    {
        $this->init();
        $this->displayContent();
    }

}