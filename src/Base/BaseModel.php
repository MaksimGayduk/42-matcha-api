<?php

namespace App\Base;

use Slim\Http\Request;

class BaseModel
{
    protected $db;
    protected $query;
    protected $queryParams;
    protected $tableName;

    public function __construct($objectDataBase)
    {
        $this->db = $objectDataBase;
    }

    public function fetchQuery(Request $request)
    {
        $this->query = $request->getAttribute('query');
        $this->queryParams = $request->getAttribute('queryParams');
        $this->tableName = $request->getAttribute('tableName');
    }

    public static function getFields()
    {
        return static::$fields;
    }

    protected function prepareData($res)
    {
        foreach ($res as $item) {
            $data['data'][] = [
                "type" => $this->tableName,
                "id" => $item['id'],
                'attributes' => [
                    array_filter($item, function($key) {
                        return $key != 'id';
                    }, ARRAY_FILTER_USE_KEY)
                ],
                'links' => [
                    'self' => $this->tableName . '/' . $item['id']
                ]
            ];
        }
        return $data;
    }
}
