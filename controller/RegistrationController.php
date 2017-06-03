<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:56
 */

namespace bagy94\controller;
require_once "Controller.php";
use bagy94\model\User;
use bagy94\utility\PageSettings;
use bagy94\utility\Response;
use bagy94\utility\Router;
use SimpleXMLElement;


class RegistrationController extends Controller
{
    const VAR_VIEW_FORM_ACTION = "formAction";
    const VAR_VIEW_RECAPTCHA_KEY_PUBL = "recaptchaPublic";

    const RECAPTCHA_PUBLIC = "6Lfv5B4TAAAAAFcbKtuJkDlXQbt3JZylci6rjSK7";
    private static $RECAPTCHA_SECRET = "6Lfv5B4TAAAAAPBdFjZlwdpmsTCYqgYG8bTglypR";


    const ARG_POST_USER_NAME = "user-name";
    const ARG_POST_EMAIL = "email";

    public static $KEY = "registration";
    protected $actions = [
        "index","postSubmit","service_check"
    ];
    protected $templates = [
        "view/registration.tpl"
    ];

    function __construct()
    {
        parent::__construct("Registracija", "registracija");
    }

    function index()
    {
        Router::reqHTTPS("registration","index");
        $this->pageAdapter->assignArrayOfVar([
            self::VAR_VIEW_FORM_ACTION=>$this->formAction(1),
            self::VAR_VIEW_RECAPTCHA_KEY_PUBL=>self::RECAPTCHA_PUBLIC
        ]);
        $this->initFiles();

        return $this->render($this->pageAdapter->getHTML());

    }

    function service_check($args){
        $xml = new SimpleXMLElement("<check/>");
        switch ($args){
            case self::ARG_POST_USER_NAME:
                $username = filter_input(INPUT_POST,self::ARG_POST_USER_NAME,FILTER_SANITIZE_STRING);
                if($username){
                    $xml->addAttribute("success",1);
                    $user = User::initByUserName($username);
                    if(is_null($user)){
                        $xml->addAttribute("exist",0);
                        $xml->addAttribute("message","Korisnik ne postoji");
                    }else{
                        $xml->addAttribute("exist",1);
                        $xml->addAttribute("message","Korisnik postoji");
                    }
                }
                else{
                    $xml->addAttribute("success",0);
                    $xml->addAttribute("message","Korisničko ime nije u ispravnom formatu");

                }
                break;
            case self::ARG_POST_EMAIL:
                $email = filter_input(INPUT_POST,self::ARG_POST_EMAIL,FILTER_SANITIZE_STRING);
                if($email){
                    $xml->addAttribute("success",1);
                    $user = User::initByEmail($email);
                    if(is_null($user)){
                        $xml->addAttribute("exist",0);
                        $xml->addAttribute("message","Korisnik ne postoji");
                    }else{
                        $xml->addAttribute("exist",1);
                        $xml->addAttribute("message","Korisnik postoji");
                    }
                }
                else{
                    $xml->addAttribute("success",0);
                    $xml->addAttribute("message","Korisničko ime nije u ispravnom formatu");

                }
                break;
            default:
                $xml->addAttribute("success",0);
                $xml->addAttribute("message","Nisu uneseni ispravni parametri");
        }
        return $this->render($xml,Response::RESPONSE_XML);
    }
    function checkEmail(){}


    function postSubmit(){

    }



    private function initFiles(){
        $this->pageAdapter->getSettings()->addJS("https://www.google.com/recaptcha/api.js");
        $this->pageAdapter->getSettings()->addCSSLocal("registration");
        $this->pageAdapter->getSettings()->addJsLocal("registration");
    }
}