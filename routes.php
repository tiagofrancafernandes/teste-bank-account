<?php

use Tiago\EbanxTeste\Controllers\AccountController;

$routes['GET']['/']     = [AccountController::class,   'index'];
$routes['POST']['reset'] = [AccountController::class,   'reset'];

return $routes;