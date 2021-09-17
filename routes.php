<?php

use Tiago\EbanxTeste\Controllers\AccountController;
use Tiago\EbanxTeste\Controllers\DbJsonController;

$routes['GET']['/']              = [AccountController::class,   'index'];
$routes['GET']['/all_accounts']  = [AccountController::class,   'allAccounts'];
$routes['POST']['/reset']        = [AccountController::class,   'reset'];
$routes['GET']['/balance']       = [AccountController::class,   'balance'];
$routes['POST']['/event']        = [AccountController::class,   'event'];
$routes['GET']['/json']          = [DbJsonController::class,    'view'];
$routes['GET']['/json/download'] = [DbJsonController::class,    'download'];

return $routes;