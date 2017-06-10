<?php

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 07.06.17.
 * Time: 01:36
 */
namespace bagy94\controller;
class MarketController extends Controller
{


    /**
     * Returns array of possible actions
     * @return callable[]
     */
    function actions()
    {
        return [
            "index"
        ];
    }

    /**
     * Returns array of templates in controller
     * @return string[]
     */
    function templates()
    {
        return [
            "shop_index.tpl"
        ];
    }
}