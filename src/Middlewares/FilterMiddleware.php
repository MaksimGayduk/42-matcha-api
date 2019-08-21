<?php

namespace App\Middlewares;

use App\Base\SqlQueryBuilder;
use App\Helpers\ArrayHelper;

class FilterMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $params = $request->getQueryParams();
        $filters = $params['filters'] ?? null;

        if (!$filters) {
            $response = $next($request, $response);
            return $response;
        }

        $query = $request->getAttribute('query');
        $filters = $this->prepareFilterData($filters);

        $query .= SqlQueryBuilder::where($filters);
        $filters = ArrayHelper::unnest($filters);

        $queryParams = $request->getAttribute('queryParams') ?? [];
        $queryParams = array_merge($queryParams, $filters);

        $request = $request->withAttribute('query', $query);
        $request = $request->withAttribute('queryParams', $queryParams);

        $response = $next($request, $response);

        return $response;
    }

    private function prepareFilterData(array $filters): array
    {
        $res = [];
        foreach ($filters as $key => $value) {
            if (strstr($value, ',')) {
                $arrNewValues = explode(',', $value);
                foreach ($arrNewValues as $newValue) {
                    $prepareKey = $key . '_' . $newValue;
                    $res[$key][$prepareKey] = $newValue;
                }
            } else {
                $res[$key] = $value;
            }
        }

        return $res;
    }
}
