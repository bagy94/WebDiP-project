<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 4.5.2017.
 * Time: 22:17
 */

namespace bagy94\webdip\wellness\utility\db;


class DbResult
{
    public $success;
    public $rows;
    private $dataSet = NULL;
    private $msg = NULL;

    /**
     * DbResult constructor.
     * @param int $success
     * @param mixed $dataSet
     * @param string $msg
     */
    public function __construct($success=0, $dataSet=NULL, $msg=NULL)
    {
        $this->success = $success;
        $this->dataSet = $dataSet;
        $this->msg = $msg;
    }

    /**
     * @return mixed|null
     */
    public function getData(){
        return $this->dataSet;
    }

    /**
     * @return null|string
     */
    public function getMsg()
    {
        return $this->msg;
    }
    public function hasData(){
        return (isset($this->dataSet) && is_array($this->dataSet) && count($this->dataSet)) || is_numeric($this->dataSet);
    }

}