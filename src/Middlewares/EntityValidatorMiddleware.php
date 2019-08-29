<?php

namespace App\Middlewares;

use App\Base\BaseException;
use App\Config\Entities;
use App\Helpers\QueryHelper;

class EntityValidatorMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $mainEntityName = QueryHelper::getMainEntityName($request);
        $entityFields = Entities::getFieldsEntities($mainEntityName);

        if (empty($entityFields)) {
            throw new BaseException(
                "The Entity [{$mainEntityName}] does not exist",
                422,
                "Unprocessable Entity"
            );
        }

        $response = $next($request, $response);

        return $response;
    }
}
