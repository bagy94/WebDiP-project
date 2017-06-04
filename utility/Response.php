<?php
/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 31.05.17.
 * Time: 15:21
 */

namespace bagy94\utility;


class Response
{
    const RESPONSE_HTML="HTML";
    const RESPONSE_JSON = "JSON";
    const RESPONSE_XML = "XML";


    private $newUrl = NULL;

    private $content;
    private $type;

    function __construct($content,$responseType="HTML")
    {
        $this->content = $content;
        $this->type = $responseType;
    }


    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }



    public function asHTML(){
        header("Content-type: text/html");
        print_r($this->content);
    }
    public function asXML(){
        header("Content-type: application/xml");
        print_r($this->content->asXML());
    }
    public function asJSON(){
        header("Content-type: application/json");
        print_r($this->content);
    }
    public function show(){
        switch (strtoupper($this->type)){
            case self::RESPONSE_XML:
                $this->asXML();
                break;
            case self::RESPONSE_JSON:
                $this->asJSON();
                break;
            default:
                $this->asHTML();
        }
    }

    public function setNewUrl($url){
        $this->newUrl = $url;
    }
}