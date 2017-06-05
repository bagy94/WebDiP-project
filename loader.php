<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 05.06.17.
 * Time: 22:06
 */
require_once "external_libs/smarty/libs/Smarty.class.php";
spl_autoload_register(function ($class){
    //var_dump($class);
    $className= end(explode("\\",$class));
    if(file_exists("controller/$className.php")){
        //var_dump($className);
        require_once "controller/$className.php";
    }
    else if(file_exists("utility/$className.php")){
        //var_dump($className);
        require_once "utility/$className.php";
    }
    else if(file_exists("model/$className.php")){
        //var_dump($className);
        require_once "model/$className.php";
    }
});