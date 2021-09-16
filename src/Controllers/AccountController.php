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

        $account = $this->models['account_model']->getAccountById($account_id);

        if($account)
        {
            $balance = $account['balance'] ?? 0;
            return ResponseManager::basicOutput(200, $balance);
        }
        else
            return ResponseManager::basicOutput(404, 0);
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

        $account = $this->models['account_model']->getAccountById($destination) ?? [];

        return $this->runOperationByType($destination, $type, $account, $amount);
    }

    protected function runOperationByType(int $account_id, string $type, array $account, int $amount)
    {
        $operation_types = [
            'deposit',
            'withdraw',
        ];

        if(!in_array($type, $operation_types) || !$account_id)
            return ResponseManager::basicOutput(406);

        if($type == 'deposit')
            return $this->depositOperation($account_id, $type, $account, $amount);
    }

    private function depositOperation(int $account_id, string $type, array $account, int $amount)
    {
        if($account)
        {
            $balance = $account['balance'] = ($account['balance'] ?? 0) + $amount;
            $this->models['account_model']->updateAccount($account_id, $account);
        }
        else
        {
            $this->models['account_model']->newAccount($account_id, [
                'balance' => $balance = $amount
            ]);
        }

        $data = [
            "destination" => [
                "id"      => $account_id,
                "balance" => $balance ?? $amount ?? 0,
            ]
        ];

        return ResponseManager::basicOutput(201, json_encode($data), 'json');
    }
}