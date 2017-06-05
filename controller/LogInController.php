<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:57
 */

namespace bagy94\controller;
use bagy94\model\Configuration;
use bagy94\model\Log;
use bagy94\model\User;
use bagy94\utility\Router;
use bagy94\utility\UserSession;

class LogInController extends Controller
{
    const VISIT_INDEX_FIRST_STEP = 3;
    const VISIT_INDEX_SECOND_STEP = 18;

    const ACTION_LOG_IN_SUCC_1 = 6;
    const ACTION_LOG_IN_UNSUCC_1 = 7;

    const ACTION_LOG_IN_SUCC_2 = 8;
    const ACTION_LOG_IN_UNSUCC_2= 9;

    const ACTION_LOG_OUT = 23;

    const ARG_POST_USERNAME = "user_name";
    const ARG_POST_PASSWORD = "password";
    const ARG_POST_REMEMBER_USER_NAME = "remember_me";
    const ARG_POST_CODE = "LogInCode";

    const VAR_VIEW_ACTION_SUBMIT_1 = "actionSubmit";
    const VAR_VIEW_ACTION_SUBMIT_2 = "actionCode";
    const VAR_COOKIE_USERNAME = "username";

    public static $KEY = "login";

    private $errors=NULL;
    private $user=NULL;
    private $cookieRemember=TRUE;

    function __construct()
    {
        parent::__construct("Prijava", "prijava signin login");
        $this->initFiles();

    }

    function index($step=NULL)
    {
        Router::reqHTTPS(self::$KEY);
        if(isset($this->errors)){
            $this->pageAdapter->assign("error",$this->errors);
        }


        if(isset($step) && $step == "2" && isset($this->user)){
            $index = 1;
            $act = self::VISIT_INDEX_SECOND_STEP;
            $this->pageAdapter->assign(self::VAR_VIEW_ACTION_SUBMIT_2, $this->formAction(2));
        }else{
            $index = 0;
            $act = self::VISIT_INDEX_FIRST_STEP;
            $this->pageAdapter->assign(self::VAR_VIEW_ACTION_SUBMIT_1, $this->formAction(1));
            if (UserSession::isLogIn()){
                $this->pageAdapter->assign(self::VAR_COOKIE_USERNAME, UserSession::coookie());
            }
        }

        Log::write($act,"Pregled stranice prijava");
        return $this->render($this->pageAdapter->getHTML($index));
    }

    /**
     * Function returns array of controller actions
     * @return string[]
     **/

    function submit($step=NULL){
        if (!$step){
            $username = $this->filterPost(self::ARG_POST_USERNAME);
            $password = $this->filterPost(self::ARG_POST_PASSWORD);
            if($username && $password){
                return $this->logInFirstStep($username);
            }else{
                $this->errors = "Korisnički podaci nisu uneseni";
                return $this->index();
            }
        }else{
            if(isset($step[0]) && is_string($step[0]) && !strcmp($step[0],"code")){
                $code = $this->filterPost(self::ARG_POST_CODE);
                if($code){
                    //$lastGenerated =
                }
            }
            else{

            }
        }

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

    private function logInFirstStep($username){
        $this->user = User::initByUserName($username);
        if($this->user){
            if($this->user->isPasswordCorrect($this->filterPost(self::ARG_POST_PASSWORD))){
                if($this->user->isActivated()){
                    if(!$this->user->isUserAccountBlocked()){
                        Log::write(self::ACTION_LOG_IN_SUCC_1,"Prvi korak prijave uspiješan",$this->user->getUserId());
                        if($this->user->getLogInType() === "2"){
                            $code = self::generate8CharString();
                            $expiration = time()+((int)Configuration::Instance()->currentTimestamp());

                            return $this->index("2");
                        }else{
                            $cookie = filter_input(INPUT_POST,self::ARG_POST_REMEMBER_USER_NAME,FILTER_DEFAULT,FILTER_REQUIRE_ARRAY)[0]==="yes";
                            //print_r(filter_input(INPUT_POST,self::ARG_POST_REMEMBER_USER_NAME,FILTER_DEFAULT,FILTER_REQUIRE_ARRAY));
                            //print "REMEMBER: ". $cookie;
                            UserSession::start($this->user->getUserId(),$this->user->getTypeId(),$this->user->getUserName(),$cookie);
                            //self::redirect("home");
                        }
                    }else{
                        Log::write(self::ACTION_LOG_IN_UNSUCC_1,"Račun blokiran",$this->user->getUserId());
                        $this->errors = "Račun blokiran";
                    }
                }else{
                    Log::write(self::ACTION_LOG_IN_UNSUCC_1,"Račun nije aktiviran",$this->user->getUserId());
                    $this->errors = "Račun nije aktiviran";
                }
            }else{
                $this->errors = "Neispravni podaci";
                Log::write(self::ACTION_LOG_IN_UNSUCC_1,"Neispravni podaci",$this->user->getUserId());
                $this->user->wrongLogIn();
            }
        }
        else{
            Log::write(self::ACTION_LOG_IN_UNSUCC_1,"Korisnik nije pronađen");
            $this->pageAdapter->assign("error","Korisnik ne postoji");
            return $this->index();
        }
        return $this->index();
    }
    function signout(){
        Log::write(self::ACTION_LOG_OUT,"Odjava korisnika iz sustava",UserSession::log());
        if (UserSession::stop()){
            return self::redirect("home");
        }
    }

    public static function generate8CharString($numberOfCharacters=8){
        $chars = '0123456!789abcdefghij@klmnop#qrst£uv$?wxyzABCDEFGHIJKLMNOPQR_STUVWXYZ!';
        $charsLength = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $numberOfCharacters; $i++) {
            $randomString .= $chars[rand(0, $charsLength - 1)];
        }
        return $randomString;
    }


    /**
     * Returns array of possible actions
     * @return callable[]
     */
    function actions()
    {
        return ["index","submit","submit/code","signout"];
    }

    /**
     * Returns array of templates in controller
     * @return string[]
     */
    function templates()
    {
        return ["view/login_step_1.tpl", "view/login_step_2.tpl","view/login_request_unlock"];
    }

}