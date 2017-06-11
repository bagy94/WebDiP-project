<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:16
 */


namespace bagy94\controller;
use bagy94\utility\Response;
use bagy94\utility\Router;
use bagy94\utility\WebPage;
use SimpleXMLElement;

abstract  class Controller implements IController
{
    const SYSTEM_ERROR_QUERY = 21;
    const SYSTEM_ERROR_READING_PARAMS = 22;
    const ACTION_SERVICE_XML = 13;
    const ACTION_SERVICE_JSON = 12;

    const RESPONSE_JSON = "json";
    const RESPONSE_XML = "xml";


    const TAG_SUCCESS = "success";
    const TAG_MESSAGE = "message";

    protected static $error=NULL;

    private static $errorTmpl = ["error.tpl"];
    /***
     * @var mixed
     */
    protected $response=NULL;
    private $responseType = Response::RESPONSE_HTML;


    private static $controllers=[
        "home"=>"HomeController",
        "login"=>"LogInController",
        "registration"=>"RegistrationController",
        //"theme"=>"ThemeService"
        "admin"=>"AdminController",
        "crud"=>"CRUDController"
    ];

    /***
     * String used for url rewrite
     * Implement it in child
     */
    public static $KEY;

    /***
     * Array of possible actions on object
     * @var array $actions
     */
    private $actions=[];
    /***
     * Array of possible templates in controller
     * @var array $templates
     */
    private $templates=[];

    /***
     * ViewAdapter
     * @var WebPage $pageAdapter
     */
    protected $pageAdapter;

    /**
     * Controller constructor.
     * @param string $title
     * @param string $keywords
     */
    function __construct($title, $keywords="page",$page=NULL)
    {
        $this->actions = $this->actions();
        $this->templates = $this->templates();
        self::$KEY = $this::$KEY;
        $this->pageAdapter =isset($page)?$page:new WebPage($this->templates,$title,NULL,$keywords);
    }

    /***
     * @inheritdoc
     */
    function hasAction($action)
    {
        //var_dump(is_array($this->actions));
        return is_array($this->actions) && in_array($action,$this->actions) && method_exists($this,$action);
    }

    /***
     * @inheritdoc
     */
    function invokeAction($action, $args = NULL)
    {
        //echo $action;
        //print_r($args);
        return $this->{$action}($args);
    }

    /**
     * Append template to template array
     * @param string $templ
     */
    function addTemplate($templ){
        array_push($this->templates,$templ);
    }

    /**
     * ppend action to action array
     * @param $action
     */
    function addAction($action){
        array_push($this->actions,$action);
    }

    /**
     * Function used for create controller's form action
     * @param int $actionIndex
     * @return string
     */
    protected function formAction($actionIndex){
        $controller = get_called_class();
        $key = $controller::$KEY;
        return sprintf("?%s=%s/%s",Router::ROUTE,$key,$this->actions[$actionIndex]);
    }

    /**
     * @param mixed $data
     * @return Response
     */
    public function render($data=NULL){
        $data = isset($data)?$data:$this->response;
        //var_dump($data);
        //var_dump($this->response);
        return new Response($data,$this->responseType);
    }

    /**
     * Checks if method exist and invoke it
     * @param $action
     * @param null $args
     * @return Response
     */
    protected function selfInvoke($action, $args=NULL){
        if($this->hasAction($action)){
            $response = $this->invokeAction($action,$args);
        }
        else{
            $response = self::showError("Action not found");
        }
        return $response;
    }

    /**
     * Invoke corresponting controller and his action.
     * @param array $urlParts
     */
    public static function invokeController($urlParts=[])
    {
        $controller = isset($urlParts["controller"])?$urlParts["controller"]:"error";
        $action = isset($urlParts["action"])?$urlParts["action"]:"index";
        $args = isset($urlParts["args"])?$urlParts["args"]:NULL;
        //print_r($args);
        if(array_key_exists($controller,self::$controllers)){
            $class =sprintf("%s\\%s",__NAMESPACE__,self::$controllers[$controller]);
            if(class_exists($class)){
                $active = new $class();
            }
        }
        //print_r($active);
        //var_dump($active);
        if(isset($active) && $active->hasAction($action)){
            $response = $active->invokeAction($action,$args);
        }
        else{
            $response = self::showError("404 Page not found");
        }
        $response->show();
    }

    /**
     * Exists current work and goes to new controller and action
     * @param $controller
     * @param string $action
     * @param null $args
     */
    protected static function redirect($controller, $action="index", $args=NULL)
    {
        $url = Router::make($controller,$action,$args);
        header("Location: $url");
        exit(1);
    }

    /**
     * Filter $_POST variable
     * @param $varName
     * @param null $post
     * @param null $filter
     * @return mixed
     */
    protected function filterPost($varName, $post=NULL, $filter=NULL){
        if($post === NULL){
            $post = INPUT_POST;
        }
        if($filter === NULL){
            $filter = FILTER_SANITIZE_STRING;
        }
        return filter_input($post,$varName,$filter);
    }

    /**
     * Shows error if action/confroller not found
     * @param $message
     * @return Response
     */
    public function showError($message){
        if(!isset(self::$error)){
            self::$error = new WebPage(self::$errorTmpl,"Greška",NULL,"error 420");
        }
        self::$error->assign("message",$message);
        return new Response(self::$error->getHTML());
    }


    protected function unsuccessXMLResponse($message){
        if(!isset($this->response)){
            $this->response = new SimpleXMLElement("<service/>");
        }
        $this->response->addAttribute(self::TAG_SUCCESS,0);
        $this->response->addAttribute(self::TAG_MESSAGE,$message);
        return $this;
    }


    protected function build($template=NULL){
        if(is_a($this->response,"SimpleXMLElement")){
            $this->responseType = Response::RESPONSE_XML;
        }else{
            $template = isset($template) && count($this->templates) > $template?$template:0;
            $this->response = $this->pageAdapter->getHTML($template);
            //var_dump($this->response);
            $this->responseType = Response::RESPONSE_HTML;
        }
        return $this;
    }
    protected function isResponseSet(){
        return isset($this->response);
    }

    /**
     * @param $message
     * @return Response
     */
    protected function failedService($message)
    {
        $this->response = new \SimpleXMLElement("<service/>");
        $this->response->addAttribute(self::TAG_SUCCESS,0);
        $this->response->addAttribute(self::TAG_MESSAGE,$message);
        return new Response($this->response,Response::RESPONSE_XML);
    }


    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     * @return Controller
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param array $actions
     * @return Controller
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param array $templates
     * @return Controller
     */
    public function setTemplates($templates)
    {
        $this->templates = $templates;
        return $this;
    }

    /**
     * @return WebPage
     */
    public function getPageAdapter()
    {
        return $this->pageAdapter;
    }

    /**
     * @param WebPage $pageAdapter
     * @return Controller
     */
    public function setPageAdapter($pageAdapter)
    {
        $this->pageAdapter = $pageAdapter;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponseType()
    {
        return $this->responseType;
    }

    /**
     * @param string $responseType
     * @return Controller
     */
    public function setResponseType($responseType)
    {
        $this->responseType = $responseType;
        if($this->responseType === Response::RESPONSE_XML){
            $this->response = new \SimpleXMLElement("<service/>");
        }
        return $this;
    }


}