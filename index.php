<?php

declare(strict_types = 1);

use Tiago\EbanxTeste\Core\RequestManager;
use Tiago\EbanxTeste\Core\RouteManager;

require_once __DIR__ . '/vendor/autoload.php';

(new RouteManager)->render([
    'server'    => $_SERVER,
    'inputs'    => $_REQUEST,
    'body'      => (new RequestManager),
]);