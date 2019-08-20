<?php

namespace App\Middlewares;

use App\Config\Entities;

class ValidatorQueryParamsNameMiddleware extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $notValidQueryParamsNames = $this->validateQueryParamsNames($request);

        if ($notValidQueryParamsNames) {
            $errors = [
                "errors" => [
                    "status" => "422 Unprocessable Entity",
                    "title" => "The parameter name [${notValidQueryParamsNames}] does not exist"
                ]
            ];
            $response = $response->withJson($errors, 422);
            return $response;
        }

        $response = $next($request, $response);

        return $response;
    }

    private function validateQueryParamsNames($request)
    {
        $paramsCmpKeys = [
            'filters' => 'filters',
            'sort' => 'sort',
            'includes' => 'includes'
        ];

        $params = $request->getQueryParams();
        $notValidParams = array_diff_key($params, $paramsCmpKeys);

        if (!empty($notValidParams))
            $notValidParams = implode(',', array_keys($notValidParams));
        return $notValidParams ?? null;
    }
}
