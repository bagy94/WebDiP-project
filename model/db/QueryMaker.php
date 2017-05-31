<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 8.5.2017.
 * Time: 17:59
 */

namespace bagy94\webdip\wellness\utility\db;


class QueryMaker
{
    private $compareOperator = "=";
    private $keyword="";
    private $tables;
    private $columnsValues;
    private $constraint;
    private $options=NULL;

    /**
     * QueryMaker constructor.
     * @param string $keyword
     * @param $tables
     * @param $columns
     * @param $constraint
     * @param null $options
     */
    public function __construct($keyword, $tables, $columns=NULL, $constraint=NULL, $options=NULL)
    {
        $this->keyword = $keyword;
        $this->tables = $tables;
        $this->columnsValues = $columns;
        $this->constraint = $constraint;
        $this->options = $options;
    }

    private function makeSelect($constraintSeparator = "and"){
        if(!isset($this->columnsValues))$this->columnsValues = "*";
        if(is_array($this->tables))$this->tables = implode(",",$this->tables);
        if(is_array($this->constraint)){
            $con = "";
            foreach ($this->constraint as $constr){

            }

        }elseif (!is_string($this->constraint)){
            $this->constraint = "";
        }else{
            $foo = $this->constraint;
            $this->constraint = " WHERE ".$foo;
        }
        return "SELECT {$this->columnsValues} FROM {$this->tables} $this->constraint $this->options";
    }

    /**
     * @param string $compareOperator
     * @return QueryMaker
     */
    public function setCompareOperator($compareOperator="=")
    {
        $this->compareOperator = $compareOperator;
        return $this;
    }


}