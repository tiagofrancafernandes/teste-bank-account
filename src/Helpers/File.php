<?php

namespace Tiago\EbanxTeste\Helpers;

class File
{
    public static function isJson(string $string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
