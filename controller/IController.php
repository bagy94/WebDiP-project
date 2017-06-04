<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 30.05.17.
 * Time: 23:20
 */

namespace bagy94\controller;


use bagy94\utility\Response;

interface IController
{
    /**
     * Invoke object action.
     * @param callable $action
     * @param null|mixed $args
     * @return Response
     */
    function invokeAction($action,$args=NULL);
    /**
     * Check if controller has action
     * @param string $action
     * @return bool
     */
    function hasAction($action);

    /**
     * Returns array of possible actions
     * @return callable[]
     */
    function actions();

    /**
     * Returns array of templates in controller
     * @return string[]
     */
    function templates();
}