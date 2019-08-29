<?php

namespace App\Middlewares;

use App\Base\BaseException;
use App\Config\Entities;
use Slim\Http\Request;
use App\Helpers\QueryHelper;

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
        $mainEntityName = QueryHelper::getMainEntityName($request);
        $attributes = Entities::getFieldsEntities($mainEntityName);

        foreach ($filters as $columnName => $value) {
            $validators = $attributes[$columnName];
            $newValues = explode(',', $value);

            foreach ($validators as $validator => $payload) {
                if (is_string($payload)) {
                    $validator = $payload;
                    $payload = [];
                }

                $errorMessage = $this->validateValues(
                    $validator, $payload, $columnName, $newValues
                );
                if (!empty($errorMessage)) {
                    throw new BaseException(
                        $errorMessage,
                        422,
                        "Unprocessable Entity"
                    );
                }
            }
        }
    }

    private function validateValues(
        string $validator,
        array $payload,
        string $columnName,
        array $values
    ): string
    {
        foreach ($values as $value) {
            if (!$validator::validate($value, $payload)) {
                return $validator::getErrorMessage($value, $columnName, $payload);
            }
        }

        return "";
    }
}
