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
    public $menu=array();
    public $css =array();
    public $js = array();

    function __construct()
    {
        $this->css["base"]=Router::css("base");
        $this->js["base"] = Router::js("base");
    }

    public function createMenu()
    {
        if(Session::isLogIn()){

        }else{
            $this->addMenuLink("Početna",Router::make("home","index"));
            $this->addMenuLink("Prijava",Router::make("login","index"));
            $this->addMenuLink("Registracija",Router::make("registration","index"));
            $this->addMenuLink("Dokumentacija",Router::make("doc","index"));
            $this->addMenuLink("O autoru",Router::make("about","index"));
        }
    }


    private function addMenuLink($title,$path){
        $this->menu[$path] = $title;
    }

    function toJson(){

    }
    function isCreatedMenu(){
        return count($this->menu);
    }
}