<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:56
 */

namespace bagy94\controller;
use bagy94\model\Configuration;
use bagy94\model\Log;
use bagy94\model\User;
use bagy94\utility\Application;
use bagy94\utility\Response;
use bagy94\utility\Router;
use bagy94\utility\UserSession;
use SimpleXMLElement;


class RegistrationController extends Controller
{
    const VISIT_REGISTRATION = 19;
    const ACTION_REGISTR_UNSUCCESS = 5;
    const ACTION_REGISTR_SUCCES = 4;
    const ACTION_ACTIVATION_SUCCESS = 1;
    const ACTION_ACTIVATION_UNSUCCESS = 20;

    const VAR_VIEW_FORM_ACTION = "formAction";
    const VAR_VIEW_RECAPTCHA_KEY_PUBL = "recaptchaPublic";
    const VAR_VIEW_ACTIVATION_LOGIN_URL = "login_url";

    const RECAPTCHA_PUBLIC = "6Lfv5B4TAAAAAFcbKtuJkDlXQbt3JZylci6rjSK7";
    const RECAPTCHA_SECRET = "6Lfv5B4TAAAAAPBdFjZlwdpmsTCYqgYG8bTglypR";

    const ARG_POST_USER_NAME = "username";
    const ARG_POST_EMAIL = "email";

    public static $KEY = "registration";

    private $formHasErrors = [];
    /***
     * @var User|null $user
     */
    private $user = NULL;

    function __construct()
    {
        parent::__construct("Registracija", "registracija");
    }


    /**
     * Returns array of possible actions
     * @return callable[]
     */
    function actions()
    {
        return ["index","service","submit","activation"];
    }

    /**
     * Returns array of templates in controller
     * @return string[]
     */
    function templates()
    {
        return ["registration.tpl","activation.tpl","mail_tpl/activation_mail.tpl"];
    }

    function index()
    {
        Router::reqHTTPS("registration");
        $this->pageAdapter->assignArrayOfVar([
            self::VAR_VIEW_FORM_ACTION=>$this->formAction(2),
            self::VAR_VIEW_RECAPTCHA_KEY_PUBL=>self::RECAPTCHA_PUBLIC
        ]);
        if(is_array($this->formHasErrors) && count($this->formHasErrors)){
            $this->pageAdapter->assign("errors",$this->formHasErrors);
        }
        $this->initFiles();

        Log::write(self::VISIT_REGISTRATION,"Pregled stranice registracija/index");
        return $this->build()->render();

    }
    function service($args=NULL){
        $this->setResponseType(Response::RESPONSE_XML);
        $action = isset($args[0])?$args[0]:"";
        switch ($action){
            case "username":
                $exist = $this->checkUserName();
                break;
            case self::ARG_POST_EMAIL:
                $exist = $this->checkEmail();
                break;
            default:
                $exist = "-2";
        }
        if(!$exist){
            $this->response->addAttribute("success",1);
            $this->response->addAttribute("exist",0);
            $this->response->addAttribute("message","Korisnik ne postoji");
        }else if($exist < "0"){
            $this->response->addAttribute("success",0);
            $this->response->addAttribute("message","Krivo uneseni podatak");
        }else{
            $this->response->addAttribute("success",1);
            $this->response->addAttribute("exist",1);
            $this->response->addAttribute("message","Korisnik već postoji");
        }
        return $this->render();
    }

    function submit(){
        //var_dump($_POST);
        //var_dump($_GET);
        $this->user = new User();
        $this->user->setName($this->filterPost("name"));
        $this->user->setSurname($this->filterInput("surname"));
        $this->user->setBirthday($this->filterInput("birthday"));
        $this->user->setGender($this->filterInput("gender"));
        $this->user->setUserName($this->filterInput("user_name"));
        $this->user->setEmail($this->filterInput("email"));
        $this->user->setPassword($this->filterInput("password"));
        $this->user->setLogInType($this->filterInput("log-in-type"));
        //var_dump($this->user);
        if(!$this->user->isRegistrationCorrect()){
            Log::write(self::ACTION_REGISTR_UNSUCCESS,"Neuspiješna registracija/".$this->user->getUserName(),$this->user->getUserId());
            $this->formHasErrors = $this->user->getErrors();
            return $this->index();
        }else if(!$this->recaptchaCheck()){
            $this->formHasErrors = ["Recaptcha nije unesena"];
            return $this->selfInvoke("index");
        }
        else{
            if($this->user->registration()){
                Log::write(self::ACTION_REGISTR_SUCCES,"Registracija/".$this->user->getUserName(),$this->user->getUserId());

                $this->pageAdapter->assignArrayOfVar([
                    "name"=>$this->user->getName(),
                    "surname"=>$this->user->getSurname(),
                    "link"=>Router::make("registration","activation",$this->user->getActivationHash())
                ]);
                $message = $this->pageAdapter->displayContent(2);
                if($this->user->sendMail("Mail za aktivaciju korisničkog računa",$message)){
                    return self::redirect("login","index");
                }
            }
        }
    }

    function activation($code){
        $this->initFiles();
        if(isset($code[0])){
            $act = filter_var($code[0],FILTER_SANITIZE_STRING);
            $user = User::checkActivationCode($act);
            //print Application::toTimeFormat(Configuration::Instance()->currentTimestamp())."<br>";
            //print Application::toTimeFormat($user->activationLinkEndsOn(Configuration::Instance()->getActivationLinkDuration()));
            if($user){
                if (Configuration::Instance()->currentTimestamp() > $user->activationLinkEndsOn(Configuration::Instance()->getActivationLinkDuration())){
                    $this->pageAdapter->assign("error","Aktivacijski link istekao<br>Novi Poslan na mail");

                    // TODO: Change it so user must ask for new activation link
                    if($user->createNewActivation()){
                        $user->sendActivationMail();
                    }else{
                        Log::write(self::SYSTEM_ERROR_QUERY,"Greška prilikom kreiranja novog linka za aktivaciju",$user->getUserId());
                    }
                    Log::write(self::ACTION_ACTIVATION_UNSUCCESS,"Aktivacijski link istekao",$user->getUserId());
                    return $this->build(1)->render();
                }else{
                    $user->activate();
                    Log::write(self::ACTION_ACTIVATION_SUCCESS,"Korisnički račun aktivan",$user->getUserId());
                    setcookie("master_cookie","accepted",time()+(86400 * 30),"/");
                    return $this->build(1)->render();
                }
            }else{
                $this->pageAdapter->assign("error","Aktivacijski link ne postoji ili je obnovljen");
                Log::action(self::ACTION_ACTIVATION_UNSUCCESS,"Aktivacijski link ne postoji u bazi:[$act]");
                return $this->build(1)->render();
            }
        }
        $this->pageAdapter->assign("error","Neispravan aktivacijski link");
        return $this->build(1)->render();
    }


    private function initFiles(){
        $this->pageAdapter->getSettings()->addJS("https://www.google.com/recaptcha/api.js");
        $this->pageAdapter->getSettings()->addCSSLocal("registration");
        $this->pageAdapter->getSettings()->addJsLocal("registration");
    }

    private function checkUserName()
    {
        $username = filter_input(INPUT_POST,self::ARG_POST_USER_NAME,FILTER_SANITIZE_EMAIL);
        Log::write(self::ACTION_SERVICE_XML,"Upotreba servisa za provjeru korisničkog imena prilikom registracije :[$username]");
        return $username?!is_null(User::initByUserName($username)):"-1";
    }

    private function checkEmail()
    {
        $email = filter_input(INPUT_POST,self::ARG_POST_EMAIL,FILTER_SANITIZE_EMAIL);
        Log::write(self::ACTION_SERVICE_XML,"Upotreba servisa za provjeru emaila prilikom registracije :[$email]");
        return $email?!is_null(User::initByEmail($email)):"-1";
    }
    private function filterInput($name,$filter=FILTER_SANITIZE_STRING,$method=INPUT_POST){
        return filter_input($method,$name,$filter);
    }
    private function recaptchaCheck(){
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $value = $this->filterInput("g-recaptcha-response");
        try {
            $data = [
                'secret'   => self::RECAPTCHA_SECRET,
                'response' => $value,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ];

            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                ]
            ];

            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            //print_r($result);
            return json_decode($result)->success == "true";
        }
        catch (Exception $e) {
            return FLASE;
        }
    }
}