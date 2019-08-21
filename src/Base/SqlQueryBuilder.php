<?php

namespace App\Base;

class SqlQueryBuilder
{

    public static function select(string $entity): ?string
    {
        $query = 'SELECT * FROM ' . $entity;

        return $query;
    }

    public static function where(array $filters): string
    {

        $shouldSeparate = count($filters) - 1;
        $query = ' WHERE';

        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $preparedString = '';
                $count = count($value) - 1;
                foreach ($value as $item) {
                    $preparedKey = $key . '_' . $item;
                    $preparedString .= ":${preparedKey}";
                    if ($count--) {
                        $preparedString .= ',';
                    }
                }
                $query .= " ${key} IN (${preparedString})";
            } else {
                $query .= " ${key}=:${key}";
            }
            if ($shouldSeparate--) {
                $query .= " AND";
            }
        }

        return $query;
    }

}
