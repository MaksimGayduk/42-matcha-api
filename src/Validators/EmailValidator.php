<?php

namespace App\Validators;

use App\Interfaces\ValidatorInterface;

class EmailValidator implements ValidatorInterface
{
    public static function validate(string $value, array $payload = []): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public static function getErrorMessage(
        string $value,
        string $columnName,
        array $payload = []
    ): string
    {
        return "Incorrect email";
    }
}
