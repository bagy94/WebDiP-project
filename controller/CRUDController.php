<?php

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 10.06.17.
 * Time: 22:04
 */
namespace bagy94\controller;
class CRUDController extends Controller
{

    //CRUD
    const ACTION_READ = "r";
    const ACTION_UPDATE = "u";
    const ACTION_CREATE = "c";
    const ACTION_DELETE = "d";




    function __construct()
    {
        parent::__construct("CRUD", "crud");
    }


    function index(){


        return $this->build()->render();
    }
    /**
     *
     * Admin curd index
     */
    function crud($args=NULL){
        if (!UserSession::isAdmin()){
            self::redirect("home");
        }
        $this->loadOnCrud();

        $action = isset($args[0])?filter_var($args[0],FILTER_SANITIZE_STRING):NULL;
        switch ($action){
            case self::ACTION_CREATE:
                break;

            default:

        }



        return $this->build()->render();
    }
    private function createService(){
        // TODO: implement insert in table
    }
    private function selectService(){

    }
    private function updateService(){

    }
    private function deleteService(){

    }


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
       return ["crud.tpl"];
    }
}