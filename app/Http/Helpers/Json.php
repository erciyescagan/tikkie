<?php

namespace App\Http\Helpers;

class Json
{
    private static $json;

    public static function setJson($request){self::$json = $request->all();}

    public static function getJson() : array {return self::$json;}
}
