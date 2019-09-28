<?php

namespace App\Validators;

use App\Base\SqlQueryBuilder;
use App\Interfaces\ValidatorInterface;

class UniqueValidator implements ValidatorInterface
{
    public static function validate($value, array $payload = []): bool
    {
        $res = true;

        if (!empty($payload)) {
            $query = SqlQueryBuilder::buildQuery($payload['entityName'], [$payload['columnName'] => $value]);

//            $select = SqlQueryBuilder::select($payload['entityName']);
//            $where = SqlQueryBuilder::where([$payload['columnName'] => $value]);
//            $query = $select . $where;

            $res = $payload['db']->executeQuery($query, [$payload['columnName'] => $value]);
            $res = empty($res);
        }

        return $res;
    }

    public static function getErrorMessage(
        $value,
        string $columnName,
        array $payload = []
    ): string
    {

        return "The field [{$columnName}] must be unique";
    }
}
