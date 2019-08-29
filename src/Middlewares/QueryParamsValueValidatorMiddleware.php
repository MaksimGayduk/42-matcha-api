<?php

namespace App\Middlewares;

use App\Base\BaseException;
use App\Config\Entities;
use Slim\Http\Request;

class QueryParamsValueValidatorMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $queryParams = $request->getQueryParams();

        if (isset($queryParams['filters'])) {
            $filters = $queryParams['filters'];
            $this->validateQueryParamsValue($request, $filters);
        }

        $response = $next($request, $response);

        return $response;
    }

    private function validateQueryParamsValue(Request $request, array $filters): void
    {
        $mainEntityName = (explode('/',  $request->getUri()->getPath()))[1];
        $attributes = Entities::getFieldsEntities($mainEntityName);

        foreach ($filters as $columnName => $value) {
            $validators = $attributes[$columnName];
            $newValues = explode(',', $value);

            foreach ($validators as $validator => $payload) {
                foreach ($newValues as $newValue) {
                    if (!$validator::validate($newValue, $payload)) {
                        $errorMessage = $validator::getErrorMessage($value, $columnName, $payload);

                        throw new BaseException(
                            $errorMessage,
                            422,
                            "Unprocessable Entity"
                        );

                    }
                }
            }
        }
    }
}
