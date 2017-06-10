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
    const SERVICE_INTERVAL = "interval";


    const ARG_POST_MAXIMUM_ROWS = "max_rows";
    const ARG_POST_LOG_IN_CODE_DURATION = "log_in_code_duration";
    const ARG_POST_ACTIVATION_LINK_DURATION = "act_link_duration";
    const ARG_POST_MAXIMUM_LOG_INS = "max_log_in";
    const ARG_POST_SESSION_DURATION = "session_duration";

    public static $KEY = "admin";
    /***
     * @var \SimpleXMLElement|null
     */
    private $xmlService = NULL;
    private $conf;


    function __construct()
    {

        parent::__construct("Administrator","admin configuration");
        $this->conf = new Configuration();
    }

    public function index()
    {
        // TODO: Log and actions
        if(!UserSession::isAdmin()){
            self::redirect("home");
        }

        $this->loadFiles();

        $this->pageAdapter->assignArrayOfVar([
            self::ARG_POST_MAXIMUM_ROWS=>$this->conf->getNoRows(),
            self::ARG_POST_ACTIVATION_LINK_DURATION =>$this->conf->getActivationLinkDuration(),
            self::ARG_POST_LOG_IN_CODE_DURATION=>$this->conf->getLoginCodeDuration(),
            self::ARG_POST_MAXIMUM_LOG_INS=>$this->conf->getMaxLogin(),
            self::ARG_POST_SESSION_DURATION=>$this->conf->getSessionDuration()
        ]);

        return $this->render($this->pageAdapter->getHTML(1));

    }


    public function service($args=NULL)
    {
        $this->xmlService = new \SimpleXMLElement("<service/>");
        if(isset($args[0])){
            $req = filter_var($args[0],FILTER_SANITIZE_STRING);
            switch ($req){
                case self::SERVICE_INTERVAL:
                    $this->xmlService->addAttribute(self::TAG_SUCCESS,1);
                    $this->xmlService->add("interval",$this->conf->getInterval());
                    break;
                case self::ARG_POST_MAXIMUM_LOG_INS:
                    $this->maxLogInService();
                    break;
                default:
                    $this->xmlService->addAttribute(self::TAG_SUCCESS,0);
                    $this->xmlService->addAttribute(self::TAG_SUCCESS,"No service found");
            }

        }else{
            $this->xmlService->addAttribute(self::TAG_SUCCESS,0);
            $this->xmlService->addAttribute(self::TAG_SUCCESS,"No service found");
        }
        return $this->render($this->xmlService,Response::RESPONSE_XML);

    }




    /**
     *  Initrs css and js files in smarty
     */
    private function loadFiles(){
        $this->pageAdapter->getSettings()->addJsLocal("jsClock");
        $this->pageAdapter->getSettings()->addJsLocal("admin_configuration");
        $this->pageAdapter->getSettings()->addCssLocal("admin_configuration");

    }

    private function maxLogInService(){
        $var = $this->filterPost(self::ARG_POST_MAXIMUM_LOG_INS);
        if($var){
            $this->xmlService->addAttribute(self::TAG_SUCCESS,$this->conf->setMaxLogin($var)->update([Configuration::$tMaxLogIn]));


            //TODO : Update doesnt work
            $this->xmlService->addChild("max_log_in",$this->conf->getMaxLogin());

            //TODO: Log
        }else{
            $this->xmlService->addAttribute(self::TAG_SUCCESS,1);
            $this->xmlService->addAttribute(self::TAG_MESSAGE,"Neispravan parametar");
        }
    }

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
            "service"
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
            "admin_configuration.tpl"
        ];
    }
}