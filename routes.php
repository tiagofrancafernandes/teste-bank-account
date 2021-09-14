<?php

use Tiago\EbanxTeste\Controllers\AccountController;

$routes['GET']['/']       = [AccountController::class,   'index'];
$routes['POST']['/reset'] = [AccountController::class,   'reset'];
$routes['GET']['/reset']  = [AccountController::class,   'reset'];//TODO Only POST. Remove it

return $routes;