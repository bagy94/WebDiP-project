<?php
use bagy94\controller\Controller;

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 09.06.17.
 * Time: 02:43
 */
class ServiceXml extends Controller
{

    /**
     * Returns array of possible actions
     * @return callable[]
     */

    function index($args=NULL){

    }



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
        return["_service.tpl"];
    }
}