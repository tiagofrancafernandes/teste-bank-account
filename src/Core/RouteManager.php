<?php

namespace Tiago\EbanxTeste\Core;

class RouteManager
{
    public function render(array $request_data)
    {
        $routes     = $this->getRoutesData();
        $server     = $request_data['server'];
        $request    = $request_data['request'];

        $path  = $server['PATH_INFO'] ?? '/';

        $method = $server['REQUEST_METHOD'] ?? 'GET';
        $method = in_array(strtoupper($method), ['GET', 'POST']) ? $method : 'GET';

        $route_value = [
            'method' => $method,
            'path'   => $path,
        ];

        $route = $routes[$method][$path] ?? null;

        if(!$route)
            throw new \Exception("Route not found!", 1);

        if(!is_array($route) || !(count($route) >= 2))
            throw new \Exception("Invalid route!", 1);

        $instance_class = (new $route[0]())->{$route[1]}($route[2] ?? null);

        return $instance_class;
    }

    public function getRoutesData()
    {
        $routes     = require_once __DIR__ .'/../../routes.php';
        return $routes;
    }

    public function responseType($data, string $response_type = 'json')
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

        $response_type = in_array($response_type, array_keys($valid_types))
                        ? $response_type : 'json';

        $mime_type = $valid_types[$response_type]['mime'] ?? 'application/json';

        header("Content-Type: ". $mime_type);

        if($response_type == 'json')
            die(print_r(json_encode(...$instance_class), true));

        echo $instance_class;die;
    }

    public function json(...$data)
    {
        // responseType
    }

}