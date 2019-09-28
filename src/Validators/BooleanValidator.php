<?php

namespace App\Validators;

use App\Interfaces\ValidatorInterface;

class BooleanValidator implements ValidatorInterface
{
    public static function validate($value, array $payload = []): bool
    {
        return $value === '1' || $value === '0' || $value === 1 || $value === 0;
    }

    public static function getErrorMessage(
        $value,
        string $columnName,
        array $payload = []
    ): string
    {
        return "Type of the column '{$columnName}' should be boolean";
    }
}
