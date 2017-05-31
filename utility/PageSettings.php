<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 28.05.17.
 * Time: 17:22
 */

namespace bagy94\utility;
require_once "UserSession.php";
use bagy94\utility\UserSession as Session;
use bagy94\utility\Router;

class PageSettings
{
    const HEADER = "header";
    const FOOTER = "footer";
    const PROP_BG_COLOR = "bgColor";
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
    public $icon;
    public $theme = [
        self::HEADER =>[
            self::PROP_BG_COLOR=>"background:#282847"
        ],
        self::FOOTER=>[
            self::PROP_BG_COLOR=>"#282847"
        ]
    ];


    function __construct()
    {
        $this->addCssLocal("base");
        $this->addJsLocal("base");
        $this->icon = Router::asset("icon");
    }

    /**
     *  Create menu links depending on user log in status and type
     */
    public function createMenu()
    {
        switch (Session::getUserType()) {
            case Session::ADMINISTRATOR:
                $this->addMenuLink("Postavke sustava", Router::make("admin", "indexSett ings"));
            case Session::MODERATOR:
            case Session::REGULAR:
            default:
                $this->addMenuLink("Početna", Router::make("home", "index"));
                $this->addMenuLink("Prijava", Router::make("login", "index"));
                $this->addMenuLink("Registracija", Router::make("registration", "index"));
                $this->addMenuLink("Dokumentacija", Router::make("doc", "index"));
                $this->addMenuLink("O autoru", Router::make("about", "index"));
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



    public function applySettings($dbJSONSettings){

    }
}