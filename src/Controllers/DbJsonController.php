<?php

namespace Tiago\EbanxTeste\Controllers;

use Tiago\EbanxTeste\Core\ResponseManager;
use Tiago\EbanxTeste\Models\Account;

class DbJsonController
{
    protected array $models;

    public function __construct()
    {
        $this->models['account_model'] = (new Account);
    }

    /**
     * @method mixed view()
     *
     * @route GET /json
     *
     * @return void
     */
    public function view(array $request_data)
    {
        $content = $this->models['account_model']->getFileContent() ?? '{}';
        return ResponseManager::basicOutput(200, $content);
    }

    /**
     * @method mixed download()
     *
     * @route GET /json/download
     *
     * @return void
     */
    public function download(array $request_data)
    {
        $content = $this->models['account_model']->getFileContent() ?? '{}';
        return ResponseManager::downloadText($content, 'json_database.json', 'text/plain');
    }
}