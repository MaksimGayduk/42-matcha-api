<?php

namespace App\Config;

use App\Helpers\ArrayHelper;
use App\Validators\BooleanValidator;
use App\Validators\DateValidator;
use App\Validators\LengthValidator;
use App\Validators\IntValidator;
use App\Validators\EmailValidator;
use App\Validators\PasswordValidator;
use App\Validators\RequiredFieldsValidator;
use App\Validators\UniqueValidator;
use App\Validators\UsernameValidator;

class Entities
{
    private static $entities = [
        'users' => [
            'id' => [
                IntValidator::class,
            ],
            'user_name' => [
                RequiredFieldsValidator::class,
                LengthValidator::class => [
                    'max' =>  12,
                    'min' => 4,
                ],
                UsernameValidator::class,
                UniqueValidator::class
            ],
            'first_name' => [
                RequiredFieldsValidator::class,
                LengthValidator::class => [
                    'max' =>  12,
                    'min' => 4,
                ],
            ],
            'last_name' => [
                LengthValidator::class => [
                    'max' =>  12,
                    'min' => 4,
                ],
            ],
            'age' => [
                IntValidator::class,
            ],
            'password' => [
                LengthValidator::class => [
                    'max' =>  12,
                    'min' => 8,
                ],
                PasswordValidator::class,
            ],
            'email' => [
                EmailValidator::class
            ],
            'checked' => [
                BooleanValidator::class,
            ],
            'date_create' => [
                DateValidator::class => [
                    'format' => 'Y-m-d H:i:s.u',
                ]
            ],
        ],
        'comments' => [
            'id' => [
                IntValidator::class,
            ],
            'users_id' => [
                IntValidator::class,
            ],
            'text' => [
                LengthValidator::class => [
                    'max' =>  100,
                    'min' => 1,
                ]
            ],
            'data_create' => [
                DateValidator::class => [
                    'format' => 'Y-m-d H:i:s.u',
                ]
            ],
        ],
        'likes' => [
            'id' => [
                IntValidator::class,
            ],
            'users_id' => [
                IntValidator::class,
            ],
            'comments_id' => [
                IntValidator::class,
            ],
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

    public static function getRequiredFieldsEntity(string $mainEntityName): ?array
    {
        $fields = self::getFieldsEntities($mainEntityName);

        foreach ($fields as $fieldsName => $fieldsValues) {
            foreach ($fieldsValues as $value) {
                if (is_string($value) && $value === RequiredFieldsValidator::class){
                    $res[] = $fieldsName;
                }
            }
        }
        $res = array_flip($res);

        return ($res ?? null);
    }
}
