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
                foreach ($value as $k => $v) {
                    $preparedString .= ":{$k}";
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

    public static function insert(string $entityName, array $bodyAttributes)
    {
        $query = "INSERT INTO {$entityName} (";
        $val = " VALUES (";
        $count = count($bodyAttributes);

        foreach ($bodyAttributes as $key => $value) {
            $query .= "$key";
            $val .= ":$key";

            if (--$count) {
                $query .= ", ";
                $val .= ", ";
            } else {
                $query .= ")";
                $val .= ")";
            }
        }
        $query = $query . $val . " RETURNING *;";

        return $query;
    }

    public static function buildQuery(string $selectEntityName = null, array $whereData = [], array $insertData = [])
    {
        $query = "";
        if (isset($selectEntityName)) {
            $query = self::select($selectEntityName);
        }
        if (!empty($whereData)) {
            $query .= self::where($whereData);
        }
        if (!empty($insertData)) {
            $query = self::insert($insertData['entityName'], $insertData['fields']);
        }

        return $query;

    }
}
