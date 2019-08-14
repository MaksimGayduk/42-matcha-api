<?php

namespace App\Models;

use App\Base\BaseModel;

class Comment extends BaseModel
{
    protected static $fields = [
        'id' => 'int',
        'users_id' => 'int',
        'text' => 'string',
        'data_create' => 'timestamp',
    ];
}
