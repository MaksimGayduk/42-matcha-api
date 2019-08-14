<?php


namespace App\Middlewares;


use App\Config\Entities;

class ValidatorRoutMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $entityName = (explode('/',  $request->getUri()->getPath()))[1];
        $entity = Entities::getFieldsEntities($entityName);

        if(empty($entity)) {
            $errors = [
                "errors" => [
                    "status" => "422 Unprocessable Entity",
                    "title" => "The Entity [${entityName}] does not exist"
                ]
            ];
            $response = $response->withJson($errors, 422);

            return $response;
        }
        $response = $next($request, $response);

        return $response;
    }
}
