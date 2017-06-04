<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 03.06.17.
 * Time: 14:35
 */

namespace bagy94\controller;
use bagy94\model\Log;
use bagy94\utility\Response;
use bagy94\utility\UserSession;

class ErrorController extends Controller
{

    private $message;
    function __construct($message)
    {
        $this->message = $message;
        parent::__construct("Greška", "error page");
    }

    function index(){
        $this->pageAdapter->assign("message",$this->message);

        Log::visit("Greška: $this->message",UserSession::log());
        return new Response($this->pageAdapter->getHTML());
    }

    /**
     * Returns array of
     * @return callable[]
     */
    function actions()
    {
        return  ["index"];
    }

    /**
     * @inheritdoc
     */

    function templates()
    {
        return ["view/error.tpl"];
    }
}