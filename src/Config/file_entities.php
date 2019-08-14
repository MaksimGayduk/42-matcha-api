<?php

return [
    'users' => [
        'id' => 'int',
        'login' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'age' => 'int',
    ],
    'comments' => [
        'id' => 'int',
        'users_id' => 'int',
        'text' => 'string',
        'data_create' => 'timestamp',
    ],
];