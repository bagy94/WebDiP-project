<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 22.4.2017.
 * Time: 16:28
 */
use bagy94\webdip\wellness\utility\Router;

require_once "application.php";
if(isset($_GET[Router::ROUTE])){
    $route = filter_input(INPUT_GET,Router::ROUTE,FILTER_SANITIZE_URL);
}else{
    $route = "error/index/?msg=404+Page+not+found";
}
Router::redirect($route);
?>

}