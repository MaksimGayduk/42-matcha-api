<?php

namespace App\Validators;

use App\Interfaces\ValidatorInterface;

class IntValidator implements ValidatorInterface
{

    public static function validate(string $value, array $payload = []): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    public static function getErrorMessage(
        string $value,
        string $columnName,
        array $payload = []
    ): string
    {
        return "Type of the column '{$columnName}' should be integer";
    }
}
