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

    public $header=[];

    public $icon;
    public $theme;


    function __construct($jsonTheme=NULL,$addBaseLocalJs = TRUE,$addBaseLocalCss=TRUE,$addIconLocal=TRUE)
    {
        if($addBaseLocalCss){
            $this->addCssLocal("base");
        }
        if($addBaseLocalJs){
            $this->addJsLocal("base");
        }
        if($addIconLocal){
            $this->icon = Router::asset("icon");
        }
        $this->theme = is_null($jsonTheme)?ThemeAdapter::defaultTheme():ThemeAdapter::parseJSON($jsonTheme);
    }

    /**
     *  Create menu links depending on user log in status and type
     */
    public function createMenu()
    {
        $this->addMenuLink("Početna", Router::make("home"));
        $this->addMenuLink("Prijava", Router::make("login",NULL,NULL,TRUE));
        if(!UserSession::isLogIn()){
            $this->addMenuLink("Registracija", Router::make("registration",NULL,NULL,TRUE));
        }
        else{
            switch (UserSession::getUserType()) {
                case UserSession::ADMINISTRATOR:
                    $this->addMenuLink("Postavke sustava", Router::make("admin","configuration"));
                    $this->addMenuLink("CRUD", Router::make("crud"));
                    $this->addMenuLink("Upravljanje korisnicima", Router::make("admin","user_control"));
                    $this->addMenuLink("Dnevnik", Router::make("admin","log"));
                case UserSession::MODERATOR:
                case UserSession::REGULAR:

                default:
            }
        }
        $this->initHeaderLinks();
    }


    /**
     * Add new link for menu
     * @param string $title
     * @param string $path
     */
    public function addMenuLink($title, $path)
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

    public function initHeaderLinks(){
        $this->header["Dokumentacija"] =Router::make("home", "doc");
        $this->header["O autoru"] =  Router::make("home", "about");
        if(UserSession::isLogIn()){
            $this->header["Odjava"] =  Router::make("login", "signout");
        }
    }
}