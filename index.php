<?php

use Tiago\EbanxTeste\Core\RouteManager;

require_once __DIR__ . '/vendor/autoload.php';

(new RouteManager)->render([
    'server'    => $_SERVER,
    'request'   => $_REQUEST,
]);