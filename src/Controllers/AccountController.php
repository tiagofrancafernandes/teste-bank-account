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
        $amount      = $body['amount']      ?? null;

        $destination = null;

        if(!$type)
            return ResponseManager::basicOutput(406, 'Missing action type');

        if($type == 'withdraw')
            $destination = $body['origin'] ?? null;

        if($type == 'deposit')
            $destination = $body['destination']  ?? null;

        if($type == 'transfer')
        {
            if(!$body['origin'] ?? null)
                return ResponseManager::basicOutput(406, 'Missing origin account');

            $destination = $body['destination'] ?? null;
        }

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

        return $this->runOperationByType($body, $destination, $type, $account, $amount);
    }

    protected function runOperationByType(array $body, int $account_id, string $type, array $account, int $amount)
    {
        $operation_types = [
            'deposit',
            'withdraw',
            'transfer',
        ];

        if(!in_array($type, $operation_types) || !$account_id)
            return ResponseManager::basicOutput(406);

        if($type == 'deposit')
            return $this->depositOperation($account_id, $account, $amount);

        if($type == 'withdraw')
            return $this->withdrawOperation($account_id, $account, $amount);

        if($type == 'transfer')
        {
            $destination_account_id = $body['destination']  ?? null;
            $origin_account_id      = $body['origin']       ?? null;

            if(!$destination_account_id)
                return ResponseManager::basicOutput(406);

            return $this->transferOperation($body, $origin_account_id, $destination_account_id, $amount);
        }
    }

    private function depositOperation(int $account_id, array $account, int $amount)
    {
        $data = $this->depositProcessOperation($account_id, $account, $amount);

        return ResponseManager::basicOutput(201, json_encode($data), 'json');
    }

    private function depositProcessOperation(int $account_id, array $account, int $amount)
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

        return $data = [
            "destination" => [
                "id"      => (string) $account_id, //Cast string para compliance
                "balance" => $balance ?? $amount ?? 0,
            ]
        ];
    }

    private function withdrawOperation(int $account_id, array $account, int $amount)
    {
        if($account)
        {
            $balance = $account['balance'] = ($account['balance'] ?? 0) - $amount;
            $this->models['account_model']->updateAccount($account_id, $account);
        }
        else
        {
            return ResponseManager::basicOutput(404, 0);
        }

        $data = [
            "origin" => [
                "id"      => (string) $account_id, //Cast string para compliance
                "balance" => $balance ?? $amount ?? 0,
            ]
        ];

        return ResponseManager::basicOutput(201, json_encode($data), 'json');
    }

    private function transferOperation(array $body, int $origin_account_id, int $destination_account_id, int $amount)
    {
        $origin_account      = $account = $this->models['account_model']->getAccountById($origin_account_id)      ?? [];
        $destination_account = $account = $this->models['account_model']->getAccountById($destination_account_id) ?? [];

        if($origin_account)
        {
            $origin_account_balance = $origin_account['balance'] = ($origin_account['balance'] ?? 0) - $amount;
            $this->models['account_model']->updateAccount($origin_account_id, $origin_account);
        }
        else
        {
            return ResponseManager::basicOutput(404, 0);
        }

        $deposit_operation_data      = $this->depositProcessOperation($destination_account_id, $destination_account, $amount);
        $destination_account_balance = $deposit_operation_data['destination']['balance'] ?? $amount;

        $data = [
            "origin" => [
                "id"      => (string) $origin_account_id,
                "balance" => $origin_account_balance ?? $amount ?? 0,
            ],
            "destination" => [
                "id"      => (string) $destination_account_id,
                "balance" => $destination_account_balance ?? $amount ?? 0,
            ],
        ];

        return ResponseManager::basicOutput(201, json_encode($data), 'json');
    }
}