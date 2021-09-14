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

        return (new $route[0]())->{$route[1]}($route[2] ?? null);
    }

    public function getRoutesData()
    {
        $routes     = require_once __DIR__ .'/../../routes.php';
        return $routes;
    }
}