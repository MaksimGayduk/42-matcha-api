<?php

namespace App\Middlewares;

use App\Models\User;

class ValidatorMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $notValidQueryParamsKeys = $this->validateQueryParamsKeys($request);
        $notValidQueryParamsValues = $this->validateQueryParamsValues($request);

        if($notValidQueryParamsKeys) {
            $response->getBody()->write("The <strong>parameters</strong> with the name <strong>[${notValidQueryParamsKeys}]</strong> is missing, refer to the documentation!");
            return $response;
        } else if($notValidQueryParamsValues) {
            $response->getBody()->write("The <strong>column</strong> with the name <strong>[${notValidQueryParamsValues}]</strong> is missing, refer to the documentation!");
            return $response;
        }

        $response = $next($request, $response);
        return $response;

    }

    private function validateQueryParamsValues($request)
    {
        $queryParams = $request->getQueryParams();
        $fields = User::getFields();

        $notValidParams = [];
        foreach ($queryParams as $params) {
            if(is_array($params)) {
                $notValidParams = $this->arrayKeyExists($params, $fields, $notValidParams);
            } else {
                $params = str_ireplace('-', '', $params);
                $params = array_flip(explode(',', $params));
                $notValidParams = $this->arrayKeyExists($params, $fields, $notValidParams);
            }
        }

        if(!empty($notValidParams))
            $notValidParams = implode(', ', $notValidParams);

        return $notValidParams ?? null;
    }


    private function validateQueryParamsKeys($request)
    {
        $paramsCmpKeys = [
            'filters' => 'filters',
            'sort' => 'filters'
        ];

        $params = $request->getQueryParams();
        $notValidParams = [];
        $notValidParams = $this->arrayKeyExists($params, $paramsCmpKeys, $notValidParams);

        if(!empty($notValidParams))
            $notValidParams = implode(',', $notValidParams);
        return $notValidParams ?? null;
    }


    private function arrayKeyExists($params, $fields, $notValidParams)
    {
        foreach ($params as $key => $value) {
            if(!array_key_exists($key , $fields))
                $notValidParams[] = $key;
        }
        return $notValidParams;
    }
}
