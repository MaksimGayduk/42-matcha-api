<?php

namespace App\Validators;

use App\Interfaces\ValidatorInterface;

class LengthValidator implements ValidatorInterface
{
    public static function validate($value, array $payload = []): bool
    {
        return strlen($value) <= $payload['max'] && strlen($value) >= $payload['min'];
    }

    public static function getErrorMessage(
        $value,
        string $columnName,
        array $payload = []
    ): string
    {
        if (strlen($value) > $payload['max']) {
            return "Length of the [{$columnName}] should be less then {$payload['max']}";
        } else if (strlen($value) < $payload['min']) {
            return "Length of the [{$columnName}] should be greater then {$payload['min']}";
        } else {
            return "";
        }
    }
}
