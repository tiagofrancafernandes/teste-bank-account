<?php

declare(strict_types = 1);

namespace Tiago\EbanxTeste\Core;

class ResponseManager
{
    public static function json($data, int $http_code = 200)
    {
        return self::responseType($data, 'json', $http_code);
    }

    public static function responseType($data, string $response_type = 'json', int $http_code = 200)
    {
        $valid_types = [
            'json' => [
                'mime' => 'application/json',
            ],
            'html' => [
                'mime' => 'text/html',
            ],
            'text' => [
                'mime' => 'text/plain',
            ],
        ];

        $http_code = self::httpCode($http_code)['code'] ?? 500;

        $response_type = in_array($response_type, array_keys($valid_types))
                        ? $response_type : 'json';

        $mime_type = $valid_types[$response_type]['mime'] ?? 'application/json';

        header("Content-Type: ". $mime_type);
        http_response_code($http_code);

        if($response_type == 'json')
            die(print_r(json_encode($data), true));

        echo $data;die;
    }

    public static function abort(int $http_code, string $abort_message_body = '')
    {
        $http_code = self::httpCode($http_code)['code'] ?? 500;

        http_response_code($http_code);
        die($abort_message_body);
    }

    public static function httpCode(int $http_code, bool $code_description = false)
    {
        $http_codes = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Moved Temporarily',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Time-out',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Large',
            415 => 'Unsupported Media Type',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Time-out',
            505 => 'HTTP Version not supported',
        ];

        if(!in_array($http_code, array_keys($http_codes)))
        {
            $code['code']        = 500;

            if($code_description)
                $code['description'] = $http_codes[500];
        }

        $code['code'] = $http_code ?? 500;

        if($code_description)
            $code['description'] = $http_codes[$http_code ?? 500];

        return $code;

    }
}