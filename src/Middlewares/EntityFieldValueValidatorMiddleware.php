<?php

namespace App\Middlewares;

use App\Base\BaseException;
use App\Config\Entities;
use App\Helpers\ArrayHelper;
use App\Validators\RequiredFieldsValidator;
use App\Validators\UniqueValidator;
use Slim\Http\Request;
use Slim\Http\Response;

class EntityFieldValueValidatorMiddleware
{
    public function __construct($objectDataBase)
    {
        $this->db = $objectDataBase;
    }

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

        $this->validateBodyAttributesValue($bodyAttributes, $entityFields, $mainEntityName);

        $response = $next($request, $response);

        return $response;
    }

    private function validateBodyAttributesValue(array $bodyAttributes, array $entityFields, string $mainEntityName): void
    {
        foreach ($entityFields as $columnName => $validators) {
            $value = ArrayHelper::get($bodyAttributes, $columnName);

            if ($this->isFieldRequired($validators) && !RequiredFieldsValidator::validate($value)) {
                $this->throwException(
                    RequiredFieldsValidator::getErrorMessage($value, $columnName)
                );
            }

            foreach ($validators as $validator => $payload) {
                if (is_string($payload)) {
                    $validator = $payload;
                    $payload = [];
                }
                if ($validator === UniqueValidator::class) {
                    $payload = [
                        'db' => $this->db,
                        'entityName' => $mainEntityName,
                        'columnName' => $columnName
                    ];
                }
                if ($value && $validator && !$validator::validate($value, $payload))
                    $errorMessage = $validator::getErrorMessage(
                        $value,
                        $columnName,
                        $payload
                    );
                if (!empty($errorMessage))
                    $this->throwException($errorMessage);
            }
        }
    }

    private function isFieldRequired(array $validators)
    {
        return in_array(RequiredFieldsValidator::class, $validators);
    }

    private function throwException(string $message): void
    {
        throw new BaseException(
            $message,
            422,
            "Unprocessable Entity");
    }
}
