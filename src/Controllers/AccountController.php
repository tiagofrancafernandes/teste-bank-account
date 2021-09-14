<?php

namespace Tiago\EbanxTeste\Controllers;

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
        header('Content-Type: application/json');

        http_response_code(301);

        return [
            'data' => [
                'Hello' => 'EBANX',
            ],
        ];
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
        header('Content-Type: application/json');

        http_response_code(404);

        return [
            'reset' => true,
        ];
    }
}