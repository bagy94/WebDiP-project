<?php

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 07.06.17.
 * Time: 17:23
 */

namespace bagy94\utility;
use Smarty;

class MySmarty extends Smarty
{



    function __construct()
    {
        parent::__construct();

        $this->setTemplateDir(Router::templateDir());
        $this->setCompileDir(Router::compileDir());

        print_r($this->getTemplateDir());
        print_r($this->getCompileDir());
    }
}