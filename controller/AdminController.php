<?php

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 07.06.17.
 * Time: 01:30
 */
namespace bagy94\controller;


use bagy94\model\Configuration;
use bagy94\utility\Response;
use bagy94\utility\UserSession;

class AdminController extends Controller
{


    //CONFIG
    const SERVICE_INTERVAL = "interval";
    const ARG_POST_MAXIMUM_ROWS = "max_rows";
    const ARG_POST_LOG_IN_CODE_DURATION = "log_in_code_duration";
    const ARG_POST_ACTIVATION_LINK_DURATION = "act_link_duration";
    const ARG_POST_MAXIMUM_LOG_INS = "max_log_in";
    const ARG_POST_SESSION_DURATION = "session_duration";
    const ARG_POST_COOKIE_DURATION = "cookie_duration";

    public static $KEY = "admin";

    //
    /***
     * @var \SimpleXMLElement|null
     */
    private $conf;


    //LOG
    const SERVICE_LOG = "log";

    function __construct()
    {

        parent::__construct("Administrator","admin configuration");
        $this->conf = new Configuration();
    }

    public function index(){


        return $this->build()->render();
    }

    /**
     * SERVICE
     * Method which returns configuration view
     * @param null $args
     * @return Response
     */
    public function configuration($args=NULL)
    {
        // TODO: Log and actions
        if(!UserSession::isAdmin()){
            self::redirect("home");
        }

        $this->pageAdapter->getSettings()->addJsLocal("jsClock");
        $this->pageAdapter->getSettings()->addJsLocal("admin_configuration");
        $this->pageAdapter->getSettings()->addCssLocal("admin_configuration");

        $this->pageAdapter->assignArrayOfVar([
            self::ARG_POST_MAXIMUM_ROWS=>$this->conf->getNoRows(),
            self::ARG_POST_ACTIVATION_LINK_DURATION =>$this->conf->getActivationLinkDuration(),
            self::ARG_POST_LOG_IN_CODE_DURATION=>$this->conf->getLoginCodeDuration(),
            self::ARG_POST_MAXIMUM_LOG_INS=>$this->conf->getMaxLogin(),
            self::ARG_POST_SESSION_DURATION=>$this->conf->getSessionDuration(),
            self::ARG_POST_COOKIE_DURATION=>$this->conf->getCookieDuration()
        ]);

        return $this->render($this->pageAdapter->getHTML(1));

    }


    /**
     * Main service of controller.
     * It redirects actions to private methods
     * @param null $args
     * @return Response
     */
    public function service($args=NULL)
    {
        $this->setResponseType(Response::RESPONSE_XML);
        if(isset($args[0])){
            $req = filter_var($args[0],FILTER_SANITIZE_STRING);
            //$args = array_slice($args,1);
            switch ($req){
                case self::SERVICE_INTERVAL:
                    $action = $this->filterPost(self::SERVICE_INTERVAL);
                    if($action && $action === "1"){
                        $this->conf->getNewIntervalFromService();
                    }
                    $this->response->addAttribute(self::TAG_SUCCESS,1);
                    $this->response->addChild(self::SERVICE_INTERVAL,$this->conf->getInterval());
                    break;
                case self::ARG_POST_MAXIMUM_LOG_INS:
                    $this->maxLogInService();
                    break;
                case self::ARG_POST_MAXIMUM_ROWS:
                    $this->maxRowsService();
                    break;
                case self::ARG_POST_SESSION_DURATION:
                    $this->sessionDurationService();
                    break;
                case self::ARG_POST_ACTIVATION_LINK_DURATION:
                    $this->maxActivationLinkService();
                    break;
                case self::ARG_POST_LOG_IN_CODE_DURATION:
                    $this->logInCodeDurationService();
                    break;
                case self::SERVICE_LOG:
                    $args = array_slice($args,1);
                    $this->logService($args);
                    break;
                default:
                    //var_dump($this->xmlService);
                    $this->response->addAttribute(self::TAG_SUCCESS,0);
                    $this->response->addAttribute(self::TAG_MESSAGE,"Neispravan parametar");
            }

        }else{
            $this->response->addAttribute(self::TAG_SUCCESS,0);
            $this->response->addAttribute(self::TAG_SUCCESS,"No service found");
        }
        return $this->render();

    }
    //SERVICES
    /**
     * Functions that correspond to user action.
     * Resonse is in XML.
     * All runneing over main service method.
     */
    private function maxRowsService(){
        $value = $this->filterPost(self::ARG_POST_MAXIMUM_ROWS);
        if($value){
            $this->response
                ->addAttribute(
                    self::TAG_SUCCESS,
                    $this->conf->setNoRows($value)
                        ->update([Configuration::$tTableRows])
                );
            //TODO : Update doesnt work
            $this->response->addChild(self::ARG_POST_MAXIMUM_ROWS,$this->conf->getNoRows());

        }else{
            $this->response->addAttribute(self::TAG_SUCCESS,0);
            $this->response->addAttribute(self::TAG_MESSAGE,"Neispravan parametar");
        }
    }
    private function maxLogInService(){
        $var = $this->filterPost(self::ARG_POST_MAXIMUM_LOG_INS);
        if($var){
            $this->response->addAttribute(self::TAG_SUCCESS,$this->conf->setMaxLogin($var)->update([Configuration::$tMaxLogIn]));


            //TODO : Update doesnt work
            $this->response->addChild(self::ARG_POST_MAXIMUM_LOG_INS,$this->conf->getMaxLogin());

            //TODO: Log
        }else{
            $this->response->addAttribute(self::TAG_SUCCESS,0);
            $this->response->addAttribute(self::TAG_MESSAGE,"Neispravan parametar");
        }
    }
    private function sessionDurationService()
    {
        $value = $this->filterPost(self::ARG_POST_SESSION_DURATION);
        if($value){
            $this->response->addAttribute(self::TAG_SUCCESS,$this->conf->setSessionDuration($value)->update([Configuration::$tSessionDuration]));
            $this->response->addChild(self::ARG_POST_SESSION_DURATION,$this->conf->getSessionDuration());
        }else{
            $this->response->addAttribute(self::TAG_SUCCESS,0);
            $this->response->addAttribute(self::TAG_MESSAGE,"Neispravan parametar");
        }
    }
    private function maxActivationLinkService()
    {
        $value = $this->filterPost(self::ARG_POST_ACTIVATION_LINK_DURATION);
        if($value){
            $this->response->addAttribute(self::TAG_SUCCESS,$this->conf->setActivationLinkDuration($value)->update([Configuration::$tActivationLinkDuration]));
            $this->response->addChild(self::ARG_POST_ACTIVATION_LINK_DURATION,$this->conf->getActivationLinkDuration());
        }else{
            $this->response->addAttribute(self::TAG_SUCCESS,0);
            $this->response->addAttribute(self::TAG_MESSAGE,"Neispravan parametar");
        }
    }
    private function logInCodeDurationService()
    {
        $value = $this->filterPost(self::ARG_POST_LOG_IN_CODE_DURATION);
        if($value){
            $this->response->addAttribute(self::TAG_SUCCESS,$this->conf->setLoginCodeDuration($value)->update([Configuration::$tLogInCodeDuration]));
            $this->response->addChild(self::ARG_POST_LOG_IN_CODE_DURATION,$this->conf->getLoginCodeDuration());
        }else{
            $this->response->addAttribute(self::TAG_SUCCESS,0);
            $this->response->addAttribute(self::TAG_MESSAGE,"Neispravan parametar");
        }
    }

    //VIEW LOG
    public function log()
    {
        if(!UserSession::isAdmin()){
            self::redirect("home");
        }
        $this->loadFiles();
        $this->pageAdapter->getSettings()->addCssLocal("table");

        return $this->build(2)->render();
    }
    //SERVICES
    private function logService($args = NULL){
        if(!is_null($args)){

        }else{

        }
    }



    //VIEW
    public function user_control()
    {
        if(!UserSession::isAdmin()){
            self::redirect("home");
        }
        $this->loadFiles();



        return $this->build(3)->render();
    }


    private function loadFiles(){
        $this->pageAdapter->getSettings()->addJsLocal("admin");
        $this->pageAdapter->getSettings()->addCssLocal("admin");
    }

    /**
     * @param $varName
     * @return mixed
     */
    protected function filterPost($varName)
    {
        return parent::filterPost($varName, NULL, FILTER_SANITIZE_NUMBER_INT); // TODO: Change the autogenerated stub
    }

    /**
     * Returns array of possible actions
     * @return callable[]
     */
    function actions()
    {
        return [
            "index",
            "service",
            "configuration",
            "crud",
            "log",
            "user_control"
        ];
    }
    /**
     * Returns array of templates in controller
     * @return string[]
     */
    function templates()
    {
        return [
            "admin_index.tpl",
            "admin_configuration.tpl",
            "admin_log.tpl",
            "admin_user_control.tpl"
        ];
    }




}