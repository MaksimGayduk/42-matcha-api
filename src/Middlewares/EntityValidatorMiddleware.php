<?php

namespace App\Middlewares;

use App\Base\BaseException;
use App\Config\Entities;

class EntityValidatorMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $entityName = (explode('/',  $request->getUri()->getPath()))[1];
        $entity = Entities::getFieldsEntities($entityName);

        if (empty($entity)) {
            throw new BaseException(
                "The Entity [${entityName}] does not exist",
                422,
                "Unprocessable Entity"
            );
        }

        $response = $next($request, $response);

        return $response;
    }
}
