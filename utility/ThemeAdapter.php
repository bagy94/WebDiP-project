<?php

/**
 * Created by PhpStorm.
 * User: bagy
 * Date: 01.06.17.
 * Time: 16:43
 */

namespace bagy94\utility;
use stdClass;

class ThemeAdapter
{
    const STYLE_HEADER = "header";
    const STYLE_BODY = "body";
    const STYLE_FOOTER = "footer";
    const STYLE_MENU = "menu";
    const STYLE_CONTENT = "content";

    const ATTR_MENU_LI = "li";
    const ATTR_MENU_A = "a";



    protected $data;

    function __construct($jsonObject=NULL)
    {
        if(!is_null($jsonObject) && $jsonObject instanceof stdClass){
            $this->data = [
                self::STYLE_HEADER=>[
                    "background_color"=>$jsonObject->{self::STYLE_HEADER}->background_color
                ],
                self::STYLE_BODY=>[
                    "background_image"=>$jsonObject->{self::STYLE_BODY}->background_image,
                    "font"=>$jsonObject->{self::STYLE_BODY}->font,
                ],
                self::STYLE_MENU=>[
                    self::ATTR_MENU_LI=>[
                        "background_color"=>$jsonObject->{self::STYLE_MENU}->{self::ATTR_MENU_LI}->background_color,
                        "box_shadow"=>$jsonObject->{self::STYLE_MENU}->{self::ATTR_MENU_LI}->box_shadow,
                    ],
                    self::ATTR_MENU_A=>[
                        "color"=>$jsonObject->{self::STYLE_MENU}->{self::ATTR_MENU_A}->color
                    ]
                ],
                self::STYLE_FOOTER=>[
                    "background_color"=>$jsonObject->{self::STYLE_FOOTER}->background_color
                ],
                self::STYLE_CONTENT=>[
                    "background_color"=>$jsonObject->{self::STYLE_CONTENT}->background_color
                ]
            ];
        }
    }

    public static function defaultTheme()
    {
        return [
            self::STYLE_HEADER=>[
                "background_color"=>"rgb(65, 4, 103)"
            ],
            self::STYLE_BODY=>[
                "background_image"=>"url(".Router::asset("background1","jpg").")",
                "font"=>"Times New Roman"
            ],
            self::STYLE_MENU=>[
                self::ATTR_MENU_LI=>[
                    "background_color"=>"rgba(127, 72, 154, 0.46)",
                    "box_shadow"=>"3px 5px 14px 2px #b4a2bb",
                ],
                self::ATTR_MENU_A=>[
                    "color"=>"rgb(65, 4, 103)"
                ]
            ],
            self::STYLE_FOOTER=>[
                "background_color"=>"rgb(65, 4, 103)"
            ],
            self::STYLE_CONTENT=>[
                "background_color"=>"rgba(127, 72, 154, 0.46)"
            ]
        ];
    }
    public function toJSON()
    {
        return json_encode($this->data);
    }

    public function toStyle()
    {
        return $this->data;
    }

    public static function parseJSON($json,$assoc=FALSE)
    {
        $foo = str_replace("\n","",str_replace("'","\"",$json));
        $data = json_decode($foo,$assoc);
        return $data;
    }
}