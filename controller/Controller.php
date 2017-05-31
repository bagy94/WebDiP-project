<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:16
 */


namespace bagy94\controller;
require_once "utility/WebPage.php";
use bagy94\utility\Router;
use bagy94\utility\WebPage;

abstract  class Controller implements IController
{
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
        $this->{$action}($args);
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

    protected function formAction($actionIndex){
        $controller = get_called_class();
        $key = $controller::$KEY;
        return sprintf("?%s=%s/%s",Router::ROUTE,$key,$this->actions[$actionIndex]);
    }
}