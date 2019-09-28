<?php

namespace App\Validators;

use App\Interfaces\ValidatorInterface;
use DateTime;

class DateValidator implements ValidatorInterface
{
    public static function validate($value, array $payload = []): bool
    {
        $date = DateTime::createFromFormat($payload['format'], $value);

        return ($date && $date->format($payload['format']) === $value);
    }

    public static function getErrorMessage(
        $value,
        string $columnName,
        array $payload = []
    ): string
    {
        return "Date must have next format [{$payload['format']}]";
    }
}
