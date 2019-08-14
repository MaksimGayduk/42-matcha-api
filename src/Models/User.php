<?php

namespace App\Models;

use App\Base\BaseModel;

class User extends BaseModel
{
    protected static $fields = [
        'id' => 'int',
        'login' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'age' => 'int',
    ];
}
