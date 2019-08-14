<?php


namespace App\Config;


class Entities
{
    private static $entities = [
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

    public static function getFieldsEntities(string $entity): array
    {
        return self::$entities[$entity];
    }
}
