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

            $shouldSeparate = count($filters) - 1;
            $query .= ' WHERE';

            foreach ($filters as $key => $value) {
                if (stristr($value, ',')) {
                    unset($filters[$key]);
                    $arrNewValues = explode(',', $value);
                    $string = '';
                    $count = count($arrNewValues) - 1;
                    foreach ($arrNewValues as $value){
                        $prepareKey = $key . '_' . $value;
                        $string .= ":${prepareKey}";
                        $filters[$prepareKey] = $value;
                        if($count--){
                            $string .= ',';
                        }
                    }
                    $query .= " ${key} IN (${string})";
                } else {
                    $query .= " ${key}=:${key}";
                }
                if ($shouldSeparate--) {
                    $query .= " AND";
                }
            }

            $queryParams = $request->getAttribute('queryParams') ?? [];
            $queryParams = array_merge($queryParams, $filters);

            $request = $request->withAttribute('query', $query);
            $request = $request->withAttribute('queryParams', $queryParams);
        }

        $response = $next($request, $response);


        return $response;
    }

}