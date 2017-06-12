<?php

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 07.06.17.
 * Time: 01:40
 */
namespace bagy94\controller;
use bagy94\model\Configuration;
use bagy94\model\Log;
use bagy94\model\User;
use bagy94\utility\PageSettings;
use bagy94\utility\Response;
use bagy94\utility\Router;
use bagy94\utility\ThemeAdapter;
use bagy94\utility\UserSession;
use bagy94\utility\WebPage;

class PrivateController extends Controller
{
    const VISIT_INDEX = 25;
    const LIMIT_START = "index_start";
    const LIMIT_STOP = "index_stop";

    const ARG_GET_PAGE = "page";
    const ARG_GET_SORT_COLUMN = "sort";
    const ARG_POST_USER_ID = "uid";

    const ARG_ACTION = "users";

    const ARG_CONTROL_ACTION_UNLOCK = "0";
    const ARG_CONTROL_ACTION_LOCK = "1";
    const ARG_CONTROL_ACTION_SEARCH= "q";



    private $maxRowsPerPage = 5;
    private $page = 1;
    private $user = NULL;
    private $sortColumn = 1;
    /***
     * @var \SimpleXMLElement|null $serviceResponse
     */

    function __construct()
    {
        $pageSettings = new PageSettings(NULL,FALSE,FALSE,FALSE);

        $pageSettings->addMenuLink("Početna",Router::Instance()->buildProjectRoot()->buildActionLink("home"));
        switch (UserSession::getUserType()) {
            case UserSession::ADMINISTRATOR:
                $pageSettings->addMenuLink("Postavke sustava", Router::Instance()->buildProjectRoot()->buildActionLink("admin"));
            case UserSession::MODERATOR:
            case UserSession::REGULAR:
            default:
                $pageSettings->addMenuLink("Prijava", Router::Instance()->buildProjectRoot()->buildActionLink("login"));
        }
        if(!UserSession::isLogIn()){
            $pageSettings->addMenuLink("Registracija", Router::Instance()->buildProjectRoot()->buildActionLink("registration"));
        }

        $page = new WebPage($this->templates(),"Privatno",$pageSettings,"privatno");

        parent::__construct("Privatno","Bla",$page);
        $this->setMaxRowsPerPage(Configuration::Instance()->getNoRows());
        $this->pageAdapter->getSmarty()->setCompileDir("../templates_c");
        $this->pageAdapter->getSmarty()->addTemplateDir("../");
    }

    public function index($args=NULL)
    {

        $this->initFiles();
        $this->pageAdapter->getSettings()->theme[ThemeAdapter::STYLE_BODY]["background_image"] = sprintf("url(%s)",Router::Instance()->buildProjectRoot()->buildLink("view/asset/background1.jpg"));
        Log::write(self::VISIT_INDEX,"Pregled private direktorija");
        return $this->build()->render();
    }

    /**
     * @param null $args
     * @return Response
     */
    public function service($args=NULL)
    {
        $control = isset($args[0])?$args[0]:NULL;
        $action = isset($args[1])?$args[1]:NULL;
        $this->setResponseType(Response::RESPONSE_XML);
        //var_dump($args);
        if(!strcmp($control,self::ARG_ACTION)){
            switch ($action){
                case self::ARG_CONTROL_ACTION_UNLOCK:$this->unlock();break;
                case self::ARG_CONTROL_ACTION_LOCK:$this->lock();break;
                case self::ARG_CONTROL_ACTION_SEARCH:$this->search();break;
                default:
                    $this->getUsers();
                }
                return $this->render();
        }
        return $this->failedService("Kontrola nije pronađena");
    }

    public function showError($message)
    {
        if(!isset(self::$error)){
            self::$error = new WebPage("../view/error.tpl","Greška",NULL,"error 420");
        }
        self::$error->assign("message",$message);
        return new Response(self::$error->getHTML());
    }


    /**
     * Calculate offset depending on page and max number of rows set in admin page
     * @return int
     */
    private function getOffset()
    {
        return ($this->page-1)*$this->maxRowsPerPage;
    }

    private function unlock(){
        $username = $this->filterPost(self::ARG_POST_USER_ID,NULL,FILTER_SANITIZE_STRING);
        if($username){
            $user = User::initByUserName($username);
            if($user){

                $this->response->addAttribute(self::TAG_SUCCESS,$user->setNumberOfWrongLogIn(0)->update([User::$tNumberOfLogIns]));
                $this->response->addAttribute(self::TAG_MESSAGE,"Korisnik otključan");
                Log::write(self::ACTION_SERVICE_XML,"Otključavanje korisnika u private direktoriju");
            }else{
                $this->unsuccessXMLResponse("Korisnik nije pronađen");
            }
        }else{
            $this->unsuccessXMLResponse("Korisničko ime prazno");

        }
    }
    private function lock(){
        $username = $this->filterPost(self::ARG_POST_USER_ID,NULL,FILTER_SANITIZE_STRING);
        if($username){
            $user = User::initByUserName($username);
            if($user){
                $foo =Configuration::Instance()->getMaxLogin();
                settype($foo,"int");
                $this->response->addAttribute(self::TAG_SUCCESS,$user->setNumberOfWrongLogIn($foo+1)->update([User::$tNumberOfLogIns]));
                $this->response->addAttribute(self::TAG_MESSAGE,"Korisnik zaključan");
                Log::write(self::ACTION_SERVICE_XML,"Zakljucavanje korisnika u private direktoriju");
            }else{
                $this->unsuccessXMLResponse("Korisnik nije pronađen");
            }
        }else{
            $this->unsuccessXMLResponse("Korisničko ime prazno");

        }
    }

    /**
     *
     */
    private function getUsers(){

        if(isset($_GET[self::ARG_GET_PAGE])) {
            $this->setPage(filter_input(INPUT_GET, self::ARG_GET_PAGE, FILTER_SANITIZE_NUMBER_INT));

        }
        if(isset($_GET[self::ARG_GET_SORT_COLUMN])){
            $this->setSortColumn($this->filterPost(self::ARG_GET_SORT_COLUMN,INPUT_GET,FILTER_SANITIZE_NUMBER_INT));
        }

        if ($this->getPage() == -1){
            $options = "ORDER BY $this->sortColumn DESC LIMIT 0,$this->maxRowsPerPage";
        }else{
            // TODO: Get last page
            $offset = $this->getOffset();
            $options = "ORDER BY $this->sortColumn LIMIT $offset,$this->maxRowsPerPage";
        }

        /**
         * @var User[] $users
         */
        $users = User::getAllAsArray(
            [
                User::$tName,
                User::$tSurname,
                User::$tUserName,
                User::$tEmail,
                User::$tPassword,
                User::$tGender,
                User::$tBirthday,
                User::$tActivationHashActivatedAt,
                User::$tNumberOfLogIns
            ],NULL,$options
        );
        $maxLogIns = Configuration::Instance()->getMaxLogin();
        foreach ($users as $user){
            $xmluser = $this->response->addChild("user");
            if($user->getNumberOfWrongLogIn() >= $maxLogIns){
                $user->setNumberOfWrongLogIn(1);
            }else{
                $user->setNumberOfWrongLogIn(0);
            }

            $user->toXML($xmluser,[
                User::$tName,
                User::$tSurname,
                User::$tUserName,
                User::$tEmail,
                User::$tPassword,
                User::$tGender,
                User::$tBirthday,
                User::$tActivationHashActivatedAt,
                User::$tNumberOfLogIns
            ]);
        }
    Log::write(self::ACTION_SERVICE_XML,"Pregled korisnika");
    }




    public function error($message)
    {

    }

    private function initFiles(){
        $this->pageAdapter->getSettings()->addJS(
            Router::Instance()->buildProjectRoot()->buildLink("view/js/base.js")
        );
        $this->pageAdapter->getSettings()->addJS(
            Router::Instance()->buildProjectRoot()->buildLink("view/js/control_private.js")
        );

        $this->pageAdapter->getSettings()->addCSS(
            Router::Instance()->buildProjectRoot()->buildLink("view/css/base.css")
        );
        $this->pageAdapter->getSettings()->addCSS(
            Router::Instance()->buildProjectRoot()->buildLink("view/css/table.css")
        );
    }


    /**
     * Returns array of possible actions
     * @return callable[]
     */
    function actions()
    {
        return ["index","service"];
    }

    /**
     * Returns array of templates in controller
     * @return string[]
     */
    function templates()
    {
        return [
            "control_private.tpl"
        ];
    }

    /**
     * @return int|mixed
     */
    public function getMaxRowsPerPage()
    {
        return $this->maxRowsPerPage;
    }

    /**
     * @param int|mixed $maxRowsPerPage
     * @return PrivateController
     */
    public function setMaxRowsPerPage($maxRowsPerPage)
    {
        settype($maxRowsPerPage,"int");
        $this->maxRowsPerPage = $maxRowsPerPage;
        return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        settype($this->page,"int");
        return $this->page;
    }

    /**
     * @param int $page
     * @return PrivateController
     */
    public function setPage($page)
    {
        settype($page,"int");
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getSortColumn()
    {
        return $this->sortColumn;
    }

    /**
     * @param int $sortColumn
     * @return PrivateController
     */
    public function setSortColumn($sortColumn)
    {
        $this->sortColumn = $sortColumn;
        return $this;
    }





}