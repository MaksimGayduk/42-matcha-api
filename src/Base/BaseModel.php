<?php

namespace App\Base;

use Slim\Http\Request;

class BaseModel
{
    protected $db;
    protected $query;
    protected $queryParams;

    public function __construct($objectDataBase)
    {
        $this->db = $objectDataBase;
    }

    public function fetchQuery(Request $request)
    {
        $this->query = $request->getAttribute('query');
        $this->queryParams = $request->getAttribute('queryParams');
    }

    public static function getFields()
    {
        return static::$fields;
    }
}
