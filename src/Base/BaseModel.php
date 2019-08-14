<?php

namespace App\Base;

use Slim\Http\Request;

class BaseModel
{
    public static function getFields()
    {
        return static::$fields;
    }
}
