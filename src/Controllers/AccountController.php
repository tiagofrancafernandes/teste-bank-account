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
    public function index(array $request_data)
    {
        return ResponseManager::json([
            'data'          => [
                'Hello' => 'EBANX',
            ],
            'success'       => true,
            'error_message' => "Route not found!",
        ], 301);
    }

    /**
     * @method mixed reset()
     *
     * @route POST /reset
     *
     * @return void
     */
    public function reset(array $request_data)
    {
        return ResponseManager::json([
            'data'          => [
                'reset' => true,
            ],
            'success'       => true,
        ], 200);
    }

    /**
     * @method mixed balance()
     *
     * @route GET /balance
     *
     * @return void
     */
    public function balance(array $request_data)
    {
        $account_id = $request_data['inputs']['account_id'] ?? null;

        if(!$account_id || !is_numeric($account_id))
            return ResponseManager::json([
                'data'          => [],
                'success'       => false,
                'error_message' => 'Invalid Account ID or Account ID not provided',
            ], 400);

        return ResponseManager::json([
            'data'          => [
                'balance' => rand(10, 100),//TODO
            ],
            'success'       => true,
        ], 200);
    }
}