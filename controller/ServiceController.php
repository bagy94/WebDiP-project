<?php

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 10.06.17.
 * Time: 21:53
 */
use bagy94\controller\Controller;
class ServiceController extends Controller
{

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
        return[];
    }
}