<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:16
 */


namespace bagy94\controller;
require_once "utility/WebPage.php";
require_once "IController.php";
use bagy94\utility\Response;
use bagy94\utility\Router;
use bagy94\utility\WebPage;
use SimpleXMLElement;

abstract  class Controller implements IController
{
    const RESPONSE_JSON = "json";
    const RESPONSE_XML = "xml";
    /***
     * String used for url rewrite
     * Implement it in child
     */
    public static $KEY;

    /***
     * Array of possible actions on object
     * @var array $actions
     */
    protected $actions=[];
    /***
     * Array of possible templates in controller
     * @var array $templates
     */
    protected $templates=[];

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
        $this->pageAdapter = new WebPage($this->templates,$title,NULL,$keywords);
    }

    /***
     * @inheritdoc
     */
    function hasAction($action)
    {
        return is_array($this->actions) && in_array($action,$this->actions);
    }

    /***
     * @inheritdoc
     */
    function invoke($action, $args = NULL)
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
}