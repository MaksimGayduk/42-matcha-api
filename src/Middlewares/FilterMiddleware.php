<?php

namespace App\Middlewares;

class FilterMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $params = $request->getQueryParams();
        $query = $request->getAttribute('query');
        $filters = $params['filters'] ?? null;

        if ($filters) {
            [$filters, $query] = $this->prepareFilterQuery($filters, $query);

            $queryParams = $request->getAttribute('queryParams') ?? [];
            $queryParams = array_merge($queryParams, $filters);

            $request = $request->withAttribute('query', $query);
            $request = $request->withAttribute('queryParams', $queryParams);
        }

        $response = $next($request, $response);

        return $response;
    }

    private function prepareFilterQuery($filters, $query)
    {
        $shouldSeparate = count($filters) - 1;
        $query .= ' WHERE';

        foreach ($filters as $key => $value) {
            if (stristr($value, ',')) {
                unset($filters[$key]);
                $arrNewValues = explode(',', $value);
                $prepareString = '';
                $count = count($arrNewValues) - 1;
                foreach ($arrNewValues as $value) {
                    $prepareKey = $key . '_' . $value;
                    $prepareString .= ":${prepareKey}";
                    $filters[$prepareKey] = $value;
                    if($count--) {
                        $prepareString .= ',';
                    }
                }
                $query .= " ${key} IN (${prepareString})";
            } else {
                $query .= " ${key}=:${key}";
            }
            if ($shouldSeparate--) {
                $query .= " AND";
            }
        }

        return [
            $filters,
            $query,
        ];
    }
}
