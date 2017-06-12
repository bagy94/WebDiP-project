<?php

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 11.06.17.
 * Time: 18:47
 */
namespace bagy94\controller;
use bagy94\model\Configuration;

abstract class TableController extends Controller
{
    const ARG_PAGE = "page";
    const ARG_SORT = "sort";


    protected $page=1;
    protected $sort=1;

    protected $maxRows = 10;


    function __construct($title, $keywords = "page", $page = NULL)
    {
        parent::__construct($title, $keywords, $page);
        $this->maxRows = Configuration::Instance()->getNoRows();
    }


    /**
     * Calculate offset for current page
     * @return int
     */
    protected function getOffset()
    {
        return ($this->page-1)*$this->maxRows;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return TableController
     */
    public function setPage($page)
    {
        $this->page =(int)$page;
        return $this;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     * @return TableController
     */
    public function setSort($sort)
    {
        $this->sort =(int)$sort;
        return $this;
    }

    /**
     * @return int|mixed
     */
    public function getMaxRows()
    {
        return $this->maxRows;
    }

    /**
     * @param int|mixed $maxRows
     * @return TableController
     */
    public function setMaxRows($maxRows)
    {
        $this->maxRows =(int)$maxRows;
        return $this;
    }

}