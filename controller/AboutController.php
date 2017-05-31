<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 9.5.2017.
 * Time: 16:45
 */

namespace bagy94\controller;


use bagy94\utility\Router;

class AboutController extends Controller
{
    public static $KEY = "about";
    protected $templates = ["view/about.tpl"];
    protected $actions = ["index"];

    function __construct()
    {
        parent::__construct("O autoru", "about");
    }

    function index()
    {
        $this->pageAdapter->getSettings()->addAsset(Router::asset("me","jpg"),"me");
        $this->pageAdapter->show();
    }
}