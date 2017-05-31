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
    const FOOTER_PATH = "view/sections/_footer.tpl";

    const VAR_TITLE = "title";
    const VAR_KEYWORDS = "keyword";
    const OBJ_SETTINGS = "ps";

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
     * @param string|string[] $temp
     * @param string $title
     * @param string $keywords
     * @param PageSettings $settings
     */
    public function __construct($temp, $title, $settings=NULL,$keywords=NULL)
    {
        $this->title = $title;
        $this->keywords = $keywords;
        $this->settings = is_null($settings)?new PageSettings():$settings;
        $this->addTemp($temp);
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
        self::smarty()->registerObject($var,$obj);
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
     * Warning: html and body tag are opened after init. Close with end() function
     */
    public function init(){
        $this->assign(self::VAR_TITLE,$this->title);
        $this->assign(self::VAR_KEYWORDS,isset($this->keywords)?$this->keywords:"");
        if(!$this->settings->isCreatedMenu()){
            $this->settings->createMenu();
        }
        self::smarty()->assignByRef(self::OBJ_SETTINGS,$this->settings);
        self::smarty()->display(self::HEADER_PATH);
    }

    /**
     * Display _footer.tpl which close body and html tags
     */
    public function end(){
        self::smarty()->display(self::FOOTER_PATH);
    }


    /***Display template specific for page.*/
    function displayContent($templateIndex)
    {
        $template = is_array($this->temp) && isset($this->temp[$templateIndex])?
            $this->temp[$templateIndex]:
            $this->temp;
        self::smarty()->display($template);
        //self::smarty()->display(self::FOOTER_PATH);
    }

    /**
     * Initialize header and menu.
     * Display Content.
     * Close HTML tags.
     */
    public function show($templIndex=0)
    {
        $this->init();
        $this->displayContent($templIndex);
        $this->end();
    }

    /**
     * Assign assoc array of variables to template.
     * Key=>variable name in template
     * Value=>$key value
     * @param $array
     * @return bool
     */
    public function assignArrayOfVar($array){
        if(!is_array($array))return FALSE;
        foreach ($array as $varName => $varValue){
            $this->assign($varName,$varValue);
        }
        return TRUE;
    }

    /**
     * Add Template.
     * @param string $temp
     */
    public function addTemp($temp)
    {
        if(is_array($temp)){
            $this->temp = &$temp;
        }
        else if(is_array($this->temp)) {
            array_push($this->temp, $temp);
        }else if(is_string($this->temp)){
            $foo = $this->temp;
            $this->temp = [
                $foo, $temp
            ];
        }else{
            $this->temp = [$temp];
        }
    }

    public function appendLink($type,$link){
        $this->settings->add($type,$link);
    }

    /**
     * Setter PageSettings.
     * @param PageSettings $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return PageSettings
     */
    public function getSettings()
    {
        return $this->settings;
    }



}