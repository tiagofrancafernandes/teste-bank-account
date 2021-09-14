<?php

declare(strict_types = 1);

namespace Tiago\EbanxTeste\Core;

class RequestManager
{
    public static function json(bool $json_array = false)
    {
        return self::getBody(true);
    }

    public static function getBody(bool $json_array = false)
    {
        $request_body_data = file_get_contents("php://input");

        if($json_array)
            return json_decode($request_body_data, true);

        return $request_body_data ?? null;
    }
}