<?php

namespace App\Interfaces;

interface ValidatorInterface
{

    public static function validate(string $value, array $payload = []): bool;

    public static function getErrorMessage(
        string $value,
        string $columnName,
        array $payload = []
    ): string;

}
