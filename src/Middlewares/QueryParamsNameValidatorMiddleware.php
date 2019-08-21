<?php

namespace App\Middlewares;

use App\Base\BaseException;
use App\Config\Entities;
use Slim\Http\Request;

class QueryParamsNameValidatorMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $notValidQueryParamsNames = $this->validateQueryParamsNames($request);

        if ($notValidQueryParamsNames) {
            throw new BaseException(
                "The parameter name [${notValidQueryParamsNames}] does not exist",
                422,
                "Unprocessable Entity"
            );
        }

        $response = $next($request, $response);

        return $response;
    }

    private function validateQueryParamsNames(Request $request): ?string
    {
        $params = $request->getQueryParams();
        $paramsCmpNames = Entities::getParamsNames();

        $notValidParams = array_diff(array_keys($params), $paramsCmpNames);

        if (!empty($notValidParams))
            $res = implode(',', ($notValidParams));

        return $res ?? null;
    }
}
