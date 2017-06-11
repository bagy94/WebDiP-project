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
use bagy94\utility\Application;
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
    const ACTION_NEW_PASSWORD = 24;

    const ARG_POST_USERNAME = "user_name";
    const ARG_POST_PASSWORD = "password";
    const ARG_POST_REMEMBER_USER_NAME = "remember_me";
    const ARG_POST_CODE = "LogInCode";
    const ARG_POST_LOST_PASSWORD = "emailForPasswordRecovery";

    const VAR_VIEW_ACTION_SUBMIT_1 = "actionSubmit";
    const VAR_VIEW_ACTION_SUBMIT_2 = "actionCode";
    const VAR_COOKIE_USERNAME = "username";

    public static $KEY = "login";

    private $errors=NULL;
    /***
     * @var User|null
     */
    private $user=NULL;
    private $activeCode=NULL;
    private $cookie = TRUE;

    function __construct()
    {
        parent::__construct("Prijava", "prijava signin login");
        $this->initFiles();

    }

    function index($args=NULL)
    {

        if(!isset($this->user)){
            Router::reqHTTPS(self::$KEY);
        }
        if(isset($this->errors)){
            $this->pageAdapter->assign("error",$this->errors);
        }

        $this->pageAdapter->assign(self::VAR_VIEW_ACTION_SUBMIT_1, $this->formAction(1));
        if (UserSession::isLogIn()){
            $this->pageAdapter->assign(self::VAR_COOKIE_USERNAME, UserSession::coookie());
        }
        $this->pageAdapter->assign("lostPassword",Router::make("login","lost_password"));
        Log::write(self::VISIT_INDEX_FIRST_STEP,"Pregled stranice prijava");
        return $this->build()->render();
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
                $uid = isset($this->user)?$this->user->getUserId():NULL;
                if($code){
                    $this->user = User::initBy(User::QUERY_INIT_BY_LOG_IN_CODE,[$code]);
                    if($this->user){
                        if($this->user->getLogInCodeEndsOn() > Application::appTimeStamp()){
                            Log::write(self::ACTION_LOG_IN_SUCC_2,"Uspiješno obavljen drugi korak prijave",$this->user->getUserId());
                            $this->successOnLogIn();
                        }else{
                            Log::write(self::ACTION_LOG_IN_UNSUCC_2,"Kod za pijavu je istekao",$uid);
                            $this->errors = "Kod je istekao.";
                            return $this->index();
                        }
                    }else{
                        Log::write(self::ACTION_LOG_IN_UNSUCC_2,"Korisnički kod je promijenjen",$uid);
                        $this->errors = "Kod nije pronađen. Pokušajte se ponovno prijaviti za novi!";
                        return $this->index();
                    }
                }
            }
            else{
                return self::showError("Nepoznata akcija");
            }
        }

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
                            $this->user->setLogInCode(self::generate8CharString($this->user->getUserName().time(),10));
                            $this->user->setLogInCodeEndsOn(date(Application::TIMESTAMP_FORMAT,Configuration::Instance()->logInCodeEndsOn()));
                            $endsOn = date("H:i:s / d-m-Y",Configuration::Instance()->logInCodeEndsOn());
                            $appTime = date("H:i:s /d-m-Y",Configuration::Instance()->currentTimestamp());
                            $link = Router::make(self::$KEY,"code",$this->user->getLogInCode());
                            //print $link;
                            $this->pageAdapter->assignArrayOfVar(
                                [
                                    "name"=>$this->user->getName(),
                                    "surname"=>$this->user->getSurname(),
                                    "expire"=>$endsOn,
                                    "code"=>$this->user->getLogInCode(),
                                    "sentOn"=>$appTime,
                                    "log_in_link"=>$link
                                ]
                            );
                            $this->user->saveLogInCode();
                            $this->user->sendMail("Kod za prijavu",$this->pageAdapter->displayContent(3));
                            $this->activeCode = NULL;
                            return $this->code();
                        }else{
                            $this->cookie = filter_input(INPUT_POST,self::ARG_POST_REMEMBER_USER_NAME,FILTER_DEFAULT,FILTER_REQUIRE_ARRAY)[0]==="yes";
                            //print_r(filter_input(INPUT_POST,self::ARG_POST_REMEMBER_USER_NAME,FILTER_DEFAULT,FILTER_REQUIRE_ARRAY));
                            //print "REMEMBER: ". $cookie;

                            // TODO: Implement where to go
                            $this->successOnLogIn();
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
            $this->errors = "Neispravni podaci";
            Log::write(self::ACTION_LOG_IN_UNSUCC_1,"Korisnik nije pronađen");
        }
        return $this->index();
    }
    function signout(){
        Log::write(self::ACTION_LOG_OUT,"Odjava korisnika iz sustava",UserSession::log());
        if (UserSession::stop()){
            return self::redirect("home");
        }
    }

    public static function generate8CharString($string,$numberOfCharacters=4){
        $chars = hash("sha256",$string.time());
        $charsLength = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $numberOfCharacters; $i++) {
            $randomString .= substr($chars,rand(0, $charsLength - 1),2);
        }
        return $randomString;
    }

    function code($linkCode=NULL){
        //var_dump($linkCode);
        if(isset($linkCode[0])){

            $this->user = User::initBy(User::QUERY_INIT_BY_LOG_IN_CODE,[$linkCode[0]]);
            //var_dump($this->user);
            if($this->user){
                $this->pageAdapter->assign("activeCode",$linkCode[0]);
            }
        }
        //var_dump($this->actions());
        $this->pageAdapter->assign(self::VAR_VIEW_ACTION_SUBMIT_2, $this->formAction(5));
        $uid = isset($this->user)?$this->user->getUserId():NULL;
        Log::write(self::VISIT_INDEX_SECOND_STEP,"Unos koda za prijavu",$uid);
        return $this->build(1)->render();
    }

    /**
     *
     */
    private function successOnLogIn(){
        if(isset($this->user)){
            $this->user->resetLogInErrors();
            UserSession::start($this->user->getUserId(),$this->user->getTypeId(),$this->user->getUserName(),$this->cookie);
        }
        self::redirect("home");
    }

    function lost_password(){
        $email = $this->filterPost(self::ARG_POST_LOST_PASSWORD);
        if($email){
            $this->user = User::initByEmail($email);
            //print_r($this->user);
            if($this->user){
                if($this->user->renewPassword(TRUE)){
                    Log::write(self::ACTION_NEW_PASSWORD,"Nova lozinka poslana na mail");
                    $this->pageAdapter->assignArrayOfVar([
                        "name"=>$this->user->getName(),
                        "surname"=>$this->user->getSurname(),
                        "password"=>$this->user->getPassword(),
                        "log_in_link"=>Router::make(self::$KEY,NULL,NULL,TRUE)
                    ]);
                    $message = $this->pageAdapter->displayContent(5);
                    if($this->user->sendMail("Zatražena je nova lozinka na wellness web aplikaciji",$message)){
                        Log::write(self::ACTION_NEW_PASSWORD,"Nova lozinka poslana na mail");
                    }else{
                        Log::write(self::ACTION_NEW_PASSWORD,"Greška prilikom slanja maila za novu lozinku");
                        $this->errors = "Greška slanja nove lozinke, kontaktirajte administratora!";
                    }
                    $this->user = NULL;
                    return $this->index();
                }
                else{
                    Log::write(self::ACTION_NEW_PASSWORD,"Greska priliko kreiranja nove lozinke");
                    $this->errors = "Greška prilikom obnove, kontaktirajte administratora!";
                }
            }else{
                $this->errors = "Korisnik nije pronađen";
                Log::write(self::ACTION_NEW_PASSWORD,"Korisnik nije pronađen");
            }
        }
        $this->pageAdapter->assign("actionName",$this->formAction(4));
        $this->pageAdapter->assign("inputName",self::ARG_POST_LOST_PASSWORD);
        if(isset($this->errors)){
            $this->pageAdapter->assign("error",$this->errors);
        }
        return $this->render($this->pageAdapter->getHTML(4));
    }

    /**
     * Returns array of possible actions
     * @return callable[]
     */
    function actions()
    {
        return ["index","submit","signout","code","lost_password",htmlspecialchars("submit/code")];
    }

    /**
     * Returns array of templates in controller
     * @return string[]
     */
    function templates()
    {
        return ["login_step_1.tpl", "login_step_2.tpl","login_request_unlock","mail_tpl/log_in_code_mail.tpl","login_request.tpl","mail_tpl/new_password_mail.tpl"];
    }

}