<?php

namespace Tiago\EbanxTeste\Models;

use Tiago\EbanxTeste\Core\ModelBase;

class Account extends ModelBase
{
    public function getAccountById(int $account_id)
    {
        $account = $this->json_database_data['accounts'][$account_id] ?? null;
        return $account ?? null;
    }

    public function newAccount(int $account_id, array $account_data)
    {
        $account = $this->json_database_data['accounts'][$account_id] = $account_data;

        $this->putDataInFile($this->json_database_data);

        return $account ?? null;
    }

    public function updateAccount(int $account_id, array $account_data)
    {
        $account = $this->json_database_data['accounts'][$account_id] = $account_data;

        $this->putDataInFile($this->json_database_data);

        return $account ?? null;
    }

    public function resetAllData(string $confirmation)
    {
        if($confirmation !== 'DROP_ALL')
            return;

        $this->clearOrCreateFile();
    }
}