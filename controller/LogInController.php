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
    const VISIT_INDEX_FIRST_STEP = 3;
    const VISIT_INDEX_SECOND_STEP = 18;

    const ACTION_LOG_IN_SUCC_1 = 6;
    const ACTION_LOG_IN_UNSUCC_1 = 7;

    const ACTION_LOG_IN_SUCC_2 = 8;
    const ACTION_LOG_IN_UNSUCC_2= 9;

    const ARG_POST_USERNAME = "user_name";
    const ARG_POST_PASSWORD = "password";

    const VAR_VIEW_ACTION_SUBMIT_1 = "actionSubmit";

    public static $KEY = "login";

    private $errors=NULL;
    private $user=NULL;

    function __construct()
    {
        parent::__construct("Prijava", "prijava signin login");
        $this->initFiles();

    }

    function index($step=NULL)
    {
        Router::reqHTTPS(self::$KEY);
        if(isset($this->errors)){
            $this->pageAdapter->assign("errors",$this->errors);
        }

        if(isset($step) && $step == "2" && isset($this->user)){
            $index = 1;
            $act = self::VISIT_INDEX_SECOND_STEP;
            $this->pageAdapter->assign(self::VAR_VIEW_ACTION_SUBMIT_1, $this->formAction(2));
        }else{
            $index = 0;
            $act = self::VISIT_INDEX_FIRST_STEP;
            $this->pageAdapter->assign(self::VAR_VIEW_ACTION_SUBMIT_1, $this->formAction(1));
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
            if($username){
                return $this->logInFIrstStep($username);
            }else{
                $this->errors = "Korisničko ime nije uneseno";
                return $this->index();
            }
        }else{
            if(isset($step[0]) && is_string($step[0]) && !strcmp($step[0],"code")){

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

    private function logInFIrstStep($username){
        $this->user = User::initByUserName($username);
        if($this->user){
            if($this->user->isPasswordCorrect($this->filterPost(self::ARG_POST_PASSWORD))){
                if($this->user->isActivated()){
                    if(!$this->user->isUserAccountBlocked()){
                        Log::write(self::ACTION_LOG_IN_SUCC_1,"Račun blokiran",$this->user->getUserId());
                            return $this->index($this->user->getLogInType());
                    }else{
                        Log::write(self::ACTION_LOG_IN_UNSUCC_1,"Račun blokiran",$this->user->getUserId());
                    }
                }else{
                    Log::write(self::ACTION_LOG_IN_UNSUCC_1,"Račun blokiran",$this->user->getUserId());
                }
            }else{
                Log::write(self::ACTION_LOG_IN_UNSUCC_1,"Neispravna lozinka",$this->user->getUserId());
                $this->user->wrondLogIn();
            }
        }
        else{
            Log::write(self::ACTION_LOG_IN_UNSUCC_1,"Korisnik nije pronađen");
            $this->pageAdapter->assign("error","Korisnik ne postoji");
            return $this->index(1);
        }
        return $this->index();
    }


    /**
     * Returns array of possible actions
     * @return callable[]
     */
    function actions()
    {
        return ["index","submit","submit/code","check"];
    }

    /**
     * Returns array of templates in controller
     * @return string[]
     */
    function templates()
    {
        return ["view/login_step_1.tpl", "view/login_step_2.tpl"];
    }

}