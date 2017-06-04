<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:29
 */

namespace bagy94\model;


interface IModel
{
    function save($columns=array());
}