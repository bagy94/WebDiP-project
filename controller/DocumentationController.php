<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 31.05.17.
 * Time: 02:52
 */

namespace bagy94\controller;
use bagy94\utility\Router;

class DocumentationController extends Controller
{
    public static $KEY="doc";

    function __construct()
    {
        parent::__construct("Dokumentacija", "doc");
    }

    function index(){
        $this->pageAdapter->getSettings()->addAsset(Router::asset("era"),"era");
        return $this->render($this->pageAdapter->getHTML());
    }

    /**
     * Returns array of possible actions
     * @return callable[]
     */
    function actions()
    {
        return ["index"];
    }

    /**
     * Returns array of templates in controller
     * @return string[]
     */
    function templates()
    {
        return ["view/doc.tpl"];
    }
}