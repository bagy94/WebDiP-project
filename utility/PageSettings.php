<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 28.05.17.
 * Time: 17:22
 */

namespace bagy94\utility;
use bagy94\utility\UserSession;

class PageSettings
{
    const HEADER = "header";
    const HTML = "html";
    const LINKS_MENU = "menu";
    const LINKS_JS = "js";
    const LINKS_CSS = "css";
    const LINKS_ASSET = "asset";

    public $links= [
        self::LINKS_MENU=>[],
        self::LINKS_CSS=>[],
        self::LINKS_JS=>[],
        self::LINKS_ASSET=>[]
    ];

    public $footer=[];

    public $icon;
    public $theme;/*[
        self::HEADER =>[
            "background_color"=>"rgb(65, 4, 103);"
        ],
        self::HTML=>[
            "background_image"=>""
        ],
        "section"=>[
            "background_color"=>"rgba(127, 72, 154, 0.46)",
            "opacity"=>"0.65"
        ],
        "li"=>[
            "background_color"=>"rgba(127, 72, 154, 0.46)",
            "shadow_color"=>"#b4a2bb",
            "text_color"=>"black"
        ],
        "font"=>"\"Times New Roman\"",
        "footer"=>"rgb(65, 4, 103)"
    ];*/


    function __construct($jsonTheme=NULL)
    {
        $this->addCssLocal("base");
        $this->addJsLocal("base");
        $this->icon = Router::asset("icon");
        $this->theme = is_null($jsonTheme)?ThemeAdapter::defaultTheme():ThemeAdapter::parseJSON($jsonTheme);
        $this->initFooter();
    }

    /**
     *  Create menu links depending on user log in status and type
     */
    public function createMenu()
    {
        switch (UserSession::getUserType()) {
            case UserSession::ADMINISTRATOR:
                $this->addMenuLink("Postavke sustava", Router::make("admin", "indexSett ings"));
            case UserSession::MODERATOR:
            case UserSession::REGULAR:
            default:
                $this->addMenuLink("Početna", Router::make("home", "index"));
                $this->addMenuLink("Prijava", Router::make("login", "index"));
                $this->addMenuLink("Registracija", Router::make("registration", "index"));
        }
    }


    /**
     * Add new link for menu
     * @param string $title
     * @param string $path
     */
    private function addMenuLink($title, $path)
    {
        $this->links[self::LINKS_MENU][$title]= $path;
    }

    function toJson()
    {

    }

    function isCreatedMenu()
    {
        return count($this->links[self::LINKS_MENU]);
    }

    public function add($type,$link)
    {
        switch ($type){
            case self::LINKS_CSS:
                array_push($this->links[self::LINKS_CSS],$link);
                break;
            case self::LINKS_JS:
                array_push($this->links[self::LINKS_JS],$link);
                break;
            case self::LINKS_ASSET:
                array_push($this->links[self::LINKS_ASSET],$link);
                break;
        }
    }

    public function addCssLocal($filename)
    {
        $this->addCSS(Router::css($filename));
    }

    public function addJsLocal($filename)
    {
        $this->addJS(Router::js($filename));
    }
    public function addJS($link){
        array_push($this->links[self::LINKS_JS],$link);
    }

    public function addCSS($link)
    {
        array_push($this->links[self::LINKS_CSS],$link);
    }

    public function addAsset($link,$indexName=NULL)
    {
        if(isset($indexName)){
            $this->links[self::LINKS_ASSET][$indexName] = $link;
        }else{
            $this->add(self::LINKS_ASSET,$link);
        }
    }

    public function backgroundImage($imageName,$extension="jpg")
    {
        return "url(".Router::asset($imageName,$extension).")";
    }

    public function parseTheme($jsonTheme){

    }

    public function initFooter(){
        $this->footer["Dokumentacija"] =Router::make("doc", "index");
        $this->footer["O autoru"] =  Router::make("about", "index");
    }
}