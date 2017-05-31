<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 30.05.17.
 * Time: 23:20
 */

namespace bagy94\controller;


interface IController
{
    /**
     * Invoke object action.
     * @param callable $action
     * @param null|mixed $args
     */
    function invoke($action,$args=NULL);
    /**
     * Check if controller has action
     * @param string $action
     * @return bool
     */
    function hasAction($action);
}