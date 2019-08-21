<?php

namespace App\Config;

use App\Helpers\ArrayHelper;

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
        'likes' => [
            'id' => 'int',
            'users_id' => 'int',
            'comments_id' => 'int',
        ],
        'title_hashes' => [
            'id' => 'int',
            'title' => 'string',
        ],
        'hashes' => [
            'id' => 'int',
            'title_hashes_id' => 'id',
            'users_id' => 'id',
        ],
    ];

    private static $relationship = [
        'users' => [
            'comments',
            'likes',
        ],

        'comments' => [ 'likes' ],
    ];

    private static $paramsNames = [
            'filters',
            'sort',
            'includes'
    ];

    public static function getFieldsEntities(string $entity): ?array
    {
        return ArrayHelper::get(self::$entities, $entity);
    }

    public static function getParamsNames()
    {
        return self::$paramsNames;
    }

    public static function getEntitiesNames(): ?array
    {
        return array_keys(self::$entities);
    }

    public static function hasRelationship(string $mainEntityName, string $relationEntityName): bool
    {
        return in_array($relationEntityName, self::$relationship[$mainEntityName]);
    }
}
