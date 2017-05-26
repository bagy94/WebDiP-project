<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 23:29
 */

namespace bagy94\webdip\wellness\model;


interface IModel
{

    function save($columns=array());
    function init($constraint=NULL);
}