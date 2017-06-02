<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 02.06.17.
 * Time: 14:55
 */

namespace bagy94\controller;
use bagy94\utility\Response;
use bagy94\utility\ThemeAdapter;
use bagy94\utility\UserSession;

require_once "Controller.php";

class ThemeService extends Controller
{
    public static $KEY = "theme";

    protected $actions=["service"];


    function __construct()
    {
        parent::__construct("Themes", "theme editor");
    }


    public function service(){
        if(UserSession::isLogIn()){
            //TODO: get last applied theme for active user
        }else{
            //print_r(json_encode(ThemeAdapter::defaultTheme()));
            return $this->render(json_encode(ThemeAdapter::defaultTheme()),Response::RESPONSE_JSON);
        }
    }
}