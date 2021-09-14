<?php

use Tiago\EbanxTeste\Controllers\AccountController;

$routes['GET']['/']              = [AccountController::class,   'index'];
$routes['POST']['/reset']        = [AccountController::class,   'reset'];
$routes['GET']['/balance']       = [AccountController::class,   'balance'];
$routes['POST']['/event']        = [AccountController::class,   'event'];

return $routes;