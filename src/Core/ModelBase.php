<?php

namespace Tiago\EbanxTeste\Core;

use Tiago\EbanxTeste\Helpers\File;

class ModelBase
{
    protected string $json_database_file;
    protected array $json_database_data;

    public function __construct()
    {
        $this->json_database_file = __DIR__.'/../../json_database.json';
        $this->json_database_data = $this->getAllData();
    }

    protected function getAllData($key = null)
    {
        $content = $this->getJsonContent();

        if(is_string($content) && File::isJson($content))
        {
            if($key)
                return json_decode($content, true)[$key] ?? [];

            return json_decode($content, true);
        }

        return [];
    }

    protected function clearOrCreateFile()
    {
        $this->putDataInFile([]);
    }

    protected function putDataInFile($data)
    {
        $data['last_modified'] = date('Y-m-d H:i:s');
        file_put_contents($this->json_database_file, json_encode($data, 128));
    }

    protected function getJsonContent()
    {
        if(!file_exists($this->json_database_file))
            $this->clearOrCreateFile();

        return file_get_contents($this->json_database_file);
    }
}