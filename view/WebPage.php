<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 26.05.17.
 * Time: 16:50
 */

namespace bagy94\webdip\wellness\controller;


class WebPage
{

    private $smarty;
    private $temp;
    private $title;
    private $keywords;

    /**
     * WebPage constructor.
     * @param \Smarty $smarty
     * @param string $temp
     * @param string $title
     * @param string $keywords
     */
    public function __construct($smarty, $temp, $title, $keywords=NULL)
    {
        $this->smarty = $smarty;
        $this->temp = $temp;
        $this->title = $title;
        $this->keywords = $keywords;
    }


    /**
     * Assign value to template variable.
     * @param string $var
     * @param mixed $val
     */
    function assign($var, $val){
        $this->smarty->assign($var,$val);
    }

    /**
     * Assign object to template variable.
     * @param string $var
     * @param object $obj
     */
    function assignObj($var, $obj){
        $this->smarty->assignByRef($var,$obj);
    }

    /**
     *Display template.
     */
    function show(){




        $this->smarty->display($this->temp);
    }

}