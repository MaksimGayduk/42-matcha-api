<?php

namespace App\Middlewares;

use App\Base\BaseException;
use App\Config\Entities;
use Slim\Http\Request;
use Slim\Http\Response;

class EntityFieldNameValidatorMiddleware
{
    public function __invoke($request, $response, $next)
    {
        /**
         * @var Request $request
         * @var Response $request
         */

        $body = json_decode($request->getBody()->__toString(), true);

        if (empty($body['data']['attributes'])) {
            throw new BaseException(
                'Attributes does not exist',
                422,
                "Unprocessable Entity");
        }

        $mainEntityName = $body['data']['type'];
        $bodyAttributes = $body['data']['attributes'];
        $entityFields = Entities::getFieldsEntities($mainEntityName);
        $this->validateBodyAttributesNames($bodyAttributes, $entityFields);

        $response = $next($request, $response);

        return $response;
    }

    private function validateBodyAttributesNames(
        array $bodyAttributes,
        array $entityFields
    ): void
    {
        foreach ($bodyAttributes as $columnName => $value) {
            if (!array_key_exists($columnName, $entityFields)) {
                $this->throwException("Invalid attribute name [{$columnName}]");
            }
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
