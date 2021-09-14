<?php

namespace Tiago\EbanxTeste\Controllers;

use Tiago\EbanxTeste\Core\ResponseManager;

class AccountController
{
    /**
     * @method mixed index()
     *
     * @route GET /
     *
     * @return void
     */
    public function index()
    {
        return ResponseManager::json([
            'data' => [
                'Hello' => 'EBANX',
            ],
        ], 301);
    }

    /**
     * @method mixed reset()
     *
     * @route POST /reset
     *
     * @return void
     */
    public function reset()
    {
        return ResponseManager::json([
            'reset' => true,
        ], 200);
    }
}