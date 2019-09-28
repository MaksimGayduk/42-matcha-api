<?php

namespace App\Validators;

use App\Interfaces\ValidatorInterface;

class RequiredFieldsValidator implements ValidatorInterface
{
    public static function validate($value, array $payload = []): bool
    {

        return !is_null($value) && $value !== "";
    }

    public static function getErrorMessage(
        $value,
        string $columnName,
        array $payload = []
    ): string
    {

        return "The field [{$columnName}] required";
    }
}
