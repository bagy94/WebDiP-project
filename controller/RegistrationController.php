<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:56
 */

namespace bagy94\controller;
require_once "Controller.php";
use bagy94\utility\PageSettings;
use bagy94\utility\Router;


class RegistrationController extends Controller
{
    const VAR_VIEW_FORM_ACTION = "formAction";
    const VAR_VIEW_RECAPTCHA_KEY_PUBL = "recaptchaPublic";

    const RECAPTCHA_PUBLIC = "6Lfv5B4TAAAAAFcbKtuJkDlXQbt3JZylci6rjSK7";
    private static $RECAPTCHA_SECRET = "6Lfv5B4TAAAAAPBdFjZlwdpmsTCYqgYG8bTglypR";

    public static $CONTROLLER = "registration";
    protected $actions = [
        "index","postSubmit"
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
        $ps = new PageSettings();
        $ps->addJS("https://www.google.com/recaptcha/api.js");
        $this->pageAdapter->setSettings($ps);
        $this->pageAdapter->show();

    }


    function postSubmit(){

    }
}