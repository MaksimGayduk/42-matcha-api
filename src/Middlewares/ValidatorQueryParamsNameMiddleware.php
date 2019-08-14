<?php

namespace App\Middlewares;

class ValidatorQueryParamsNameMiddleware extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $notValidQueryParamsNames = $this->validateQueryParamsNames($request);

        if($notValidQueryParamsNames) {
            $errors = [
                "errors" => [
                    "status" => "422 Unprocessable Entity",
                    "title" => "The parameter_Name [${notValidQueryParamsNames}] does not exist"
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
            'sort' => 'filters'
        ];

        $params = $request->getQueryParams();
        $notValidParams = [];
        $notValidParams = $this->checkExistKey($params, $paramsCmpKeys, $notValidParams);

        if(!empty($notValidParams))
            $notValidParams = implode(',', $notValidParams);
        return $notValidParams ?? null;
    }
}
