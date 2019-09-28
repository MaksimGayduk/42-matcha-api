<?php

namespace App\Interfaces;

interface ValidatorInterface
{

    public static function validate($value, array $payload = []): bool;

    public static function getErrorMessage(
        $value,
        string $columnName,
        array $payload = []
    ): string;

}
