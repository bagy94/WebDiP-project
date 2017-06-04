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

abstract  class Controller implements IController
{
    const RESPONSE_JSON = "json";
    const RESPONSE_XML = "xml";

    private static $controllers=[
        "home"=>"HomeController",
        "login"=>"LogInController",
        "registration"=>"RegistrationController",
        "doc"=>"DocumentationController",
        "about"=>"AboutController",
        "theme"=>"ThemeService"
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
    function __construct($title, $keywords="page")
    {
        $this->actions = $this->actions();
        $this->templates = $this->templates();
        self::$KEY = $this::$KEY;
        $this->pageAdapter = new WebPage($this->templates,$title,NULL,$keywords);
    }

    /***
     * @inheritdoc
     */
    function hasAction($action)
    {
        return is_array($this->actions) && in_array($action,$this->actions) && method_exists($this,$action);
    }

    /***
     * @inheritdoc
     */
    function invokeAction($action, $args = NULL)
    {
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
     * @param string $responseType
     * @return Response
     */
    public function render($data, $responseType="HTML"){
        return new Response($data,$responseType);
    }

    protected function selfInvoke($action,$args=NULL){
        if($this->hasAction($action)){
            $response = $this->invokeAction($action,$args);
        }
        else{
            $error = new ErrorController("Action not found");
            $response = $error->invokeAction("index");
        }
        return $response;
    }

    public static function invokeController($controller,$action,$ars=NULL)
    {
        if(array_key_exists($controller,self::$controllers)){
            $class =sprintf("%s\\%s",__NAMESPACE__,self::$controllers[$controller]);
            if(class_exists($class)){
                $active = new $class();
            }
            else{
                $active = new ErrorController("404 page not found");
                $action = "index";
            }
        }else{
            $active = new ErrorController("404 page not found");
            $action = "index";
        }
        //print_r($active);
        if($active->hasAction($action)){
            $response = $active->invokeAction($action,$ars);
        }
        else{
            $error = new ErrorController("Action not found");
            $response = $error->invokeAction("index");
        }
        $response->show();
    }

    protected static function redirect($controller,$action="index",$args=NULL)
    {
        $url = Router::make($controller,$action,$args);
        header("Location: $url");
        exit(1);
    }
}