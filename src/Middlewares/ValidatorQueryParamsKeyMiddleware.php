<?php

namespace App\Middlewares;

use App\Config\Entities;
use App\Models\User;

class ValidatorQueryParamsKeyMiddleware extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $notValidQueryParamsKeys = $this->validateQueryParamsKeys($request);

        if($notValidQueryParamsKeys) {
            $errors = [
                "errors" => [
                    "status" => "422 Unprocessable Entity",
                    "title" => "The parameter_Key [${notValidQueryParamsKeys}] does not exist"
                ]
            ];
            $response = $response->withJson($errors, 422);
            return $response;
        }

        $response = $next($request, $response);
        return $response;
    }

    private function validateQueryParamsKeys($request)
    {
        $queryParams = $request->getQueryParams();
        $entity = (explode('/',  $request->getUri()->getPath()))[1];;
        $fields = Entities::getFieldsEntities($entity);

        $notValidParams = [];
        foreach ($queryParams as $params) {
            if(is_array($params)) {
                $notValidParams = $this->checkExistKey($params, $fields, $notValidParams);
            } else {
                $params = str_ireplace('-', '', $params);
                $params = array_flip(explode(',', $params));
                $notValidParams = $this->checkExistKey($params, $fields, $notValidParams);
            }
        }

        if(!empty($notValidParams))
            $notValidParams = implode(', ', $notValidParams);

        return $notValidParams ?? null;
    }
}
