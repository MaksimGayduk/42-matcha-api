<?php

namespace App\Middlewares;

use App\Base\BaseException;
use App\Config\Entities;
use App\Models\User;

class QueryParamsKeyValidatorMiddleware extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $this->validateQueryParamsKeys($request, $response);

        $response = $next($request, $response);

        return $response;
    }

    private function validateQueryParamsKeys($request): void
    {
        $queryParams = $request->getQueryParams();
        $mainEntityName = (explode('/',  $request->getUri()->getPath()))[1];;
        $attributes = Entities::getFieldsEntities($mainEntityName);

        foreach ($queryParams as $paramsKey => $paramsValue) {
            if (is_array($paramsValue)) {
                $this->validateArrayParams($paramsValue, $attributes);
            } else {
                $this->validateStringParams($paramsKey,  $paramsValue,  $mainEntityName,  $attributes);
            }
        }
    }

    private function validateArrayParams(array $paramsValue, array $attributes): void
    {
        $notValidParams =  implode(', ', (array_diff(array_keys($paramsValue), array_keys($attributes))));
        if ($notValidParams !== "") {
            $this->throwException("The parameter key [{$notValidParams}] does not exist");
        }
    }

    private function validateStringParams(
        string $paramsKey,
        string $paramsValue,
        string $mainEntityName,
        array $attributes
    ): void
    {
        $paramsValue = explode(',', (str_replace('-', '', $paramsValue)));
        if ($paramsKey === 'includes') {
            $entitiesNames = Entities::getEntitiesNames();
            $notValidParams = implode(', ', array_diff($paramsValue, $entitiesNames));
            if ($notValidParams) {
                $this->throwException("The parameter key [{$notValidParams}] does not exist");

            }
            foreach ($paramsValue as $param) {
                if (!Entities::hasRelationship($mainEntityName , $param) && $param !== '') {
                    $this->throwException("The relationship (includes) [{$mainEntityName} to {$param}] does not exist");
                }
            }
        }
        $notValidParams = implode(', ', array_diff($paramsValue, array_keys($attributes)));
        if ($notValidParams) {
            $this->throwException("The parameter key [{$notValidParams}] does not exist");
        }
    }

    private function throwException(string $message): void
    {
        throw new BaseException(
            $message,
            422,
            "Unprocessable Entity");
    }
}
