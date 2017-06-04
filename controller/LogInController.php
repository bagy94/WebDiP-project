<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:57
 */

namespace bagy94\controller;
use bagy94\model\Log;
use bagy94\model\User;
use bagy94\utility\Router;
use bagy94\utility\UserSession;

class LogInController extends Controller
{
    const ARG_POST_USERNAME = "user_name";
    const ARG_POST_PASSWORD = "password";

    const VAR_VIEW_ACTION_SUBMIT_1 = "actionSubmit";

    public static $KEY = "login";

    private $errors=[];

    function __construct()
    {
        parent::__construct("Prijava", "prijava signin login");
        $this->initFiles();
    }

    function index($step=NULL)
    {
        Router::reqHTTPS(self::$KEY,$this->actions()[0]);
        $this->pageAdapter->assign(self::VAR_VIEW_ACTION_SUBMIT_1, $this->formAction(1));
        if(count($this->errors)){
            $this->pageAdapter->assign("errors",$this->errors);
        }

        Log::visit("Prijava",UserSession::log());
        return $this->render($this->pageAdapter->getHTML());
    }

    /**
     * Function returns array of controller actions
     * @return string[]
     **/

    function submit(){

        if(isset($_POST[self::ARG_POST_USERNAME]) && isset($_POST[self::ARG_POST_USERNAME])){
            $username = $this->filter_post(self::ARG_POST_USERNAME);
            $user = User::initByUserName($username);

            if($user !== NULL){

            }else{
                $this->errors = ["Korisnik ne postoji!"];
            }

        }else{
            return $this->index();
        }

        Log::action("Prijava", $username);
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


    /**
     * Returns array of possible actions
     * @return callable[]
     */
    function actions()
    {
        return ["index","submit","postCode","check"];
    }

    /**
     * Returns array of templates in controller
     * @return string[]
     */
    function templates()
    {
        return ["view/login_step_1.tpl", "view/login_step_2.tpl"];
    }
    private function filter_post($varName,$filter=FILTER_SANITIZE_STRING){
        return filter_input(INPUT_POST,$varName,$filter);
    }
}