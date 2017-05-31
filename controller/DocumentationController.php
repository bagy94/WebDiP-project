<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 31.05.17.
 * Time: 02:52
 */

namespace bagy94\controller;
use bagy94\utility\PageSettings;
use bagy94\utility\Router;

require_once "Controller.php";

class DocumentationController extends Controller
{
    public static $KEY="doc";
    protected $actions = ["index"];
    protected $templates = ["view/doc.tpl"];

    function __construct()
    {
        parent::__construct("Dokumentacija", "doc");
    }

    function index(){
        $this->pageAdapter->getSettings()->addAsset(Router::asset("era"),"era");
        return $this->render($this->pageAdapter->getHTML());
    }
}