<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:57
 */

namespace bagy94\controller;
use bagy94\utility\PageSettings;
use bagy94\utility\Router;
use bagy94\utility\WebPage;

require_once "Controller.php";

class LogInController extends Controller
{
    const ARG_POST_USERNAME = "user_name";
    const ARG_POST_PASSWORD = "password";

    const VAR_VIEW_ACTION_SUBMIT_1 = "actionSubmit";

    public static $KEY = "login";

    protected $actions = ["index","submit","postCode","check"];
    protected $templates = [
        "view/login_step_1.tpl",
        "view/login_step_2.tpl"
    ];

    function __construct()
    {
        parent::__construct("Prijava", "prijava signin login");
        $this->initFiles();
    }

    function index()
    {
        Router::reqHTTPS(self::$KEY,$this->actions[0]);
        $this->pageAdapter->assign(self::VAR_VIEW_ACTION_SUBMIT_1, $this->formAction(1));


        return $this->render($this->pageAdapter->getHTML());
    }

    /**
     * Function returns array of controller actions
     * @return string[]
     **/

    function submit(){

        if(isset($_POST[self::ARG_POST_USERNAME]) && isset($_POST[self::ARG_POST_USERNAME])){
            $username = filter_input(INPUT_POST,self::ARG_POST_USERNAME,FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST,self::ARG_POST_PASSWORD,FILTER_SANITIZE_STRING);
        }else{

        }

        return $this->render($this->pageAdapter->getHTML(1));
    }
    function postCode(){

    }
    private function initFiles(){
        $this->pageAdapter->getSettings()->addCssLocal("login");
        $this->pageAdapter->getSettings()->addJsLocal("login");
    }

    public function check()
    {

    }

}