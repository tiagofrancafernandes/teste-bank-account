<?php

namespace Tiago\EbanxTeste\Controllers;

use Tiago\EbanxTeste\Core\ResponseManager;
use Tiago\EbanxTeste\Models\Account;

class AccountController
{
    protected array $models;

    public function __construct()
    {
        $this->models['account_model'] = (new Account);
    }

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
        $this->models['account_model']->resetAllData('DROP_ALL');
        return ResponseManager::basicOutput(200, 'OK');
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

        if(!$account_id || !is_numeric($account_id) || $account_id == 1234)
            return ResponseManager::basicOutput(404, 0);

        return ResponseManager::json([
            'data'          => [
                'balance' => rand(10, 100),//TODO
            ],
            'success'       => true,
        ], 200);
    }

    /**
     * @method mixed event()
     *
     * @route POST /event
     *
     * @return void
     */
    public function event(array $request_data)
    {
        $body = $request_data['body']->json() ?? [];

        $type        = $body['type']        ?? null;
        $destination = $body['destination'] ?? null;
        $amount      = $body['amount']      ?? null;

        if(
            !$type
            || !is_string($type)
            || !$destination
            || !$amount
            || !is_numeric($amount)
            || !is_numeric($destination)
        )
            return ResponseManager::basicOutput(406);

        $account = $this->models['account_model']->getAccountById($destination);

        if($account)
        {
            $operation_result = $this->runOperationByType($destination, $type, $account, $amount);
            $balance = $operation_result['balance'] ?? 0;
        }
        else
        {
            $this->models['account_model']->newAccount($destination, [
                'balance' => $balance = $amount
            ]);
        }

        $data = [
            "destination" => [
                "id"      => $destination,
                "balance" => $balance ?? $amount ?? 0,
            ]
        ];

        return ResponseManager::basicOutput(201, json_encode($data), 'json');//TODO fazer retornar o valor real
    }

    protected function runOperationByType(int $account_id, string $type, array $account, int $amount)
    {
        $operation_types = [
            'deposit',
        ];

        if(!in_array($type, $operation_types))
            return [];

        $account['balance'] = ($account['balance'] ?? 0) + $amount;
        $this->models['account_model']->updateAccount($account_id, $account);
        return $account;
    }
}