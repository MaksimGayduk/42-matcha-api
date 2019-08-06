<?php


namespace App\Middlewares;


class SortMiddleware
{

    public function __invoke($request, $response, $next)
    {
        $params = $request->getQueryParams();
        $query = $request->getAttribute('query');
        $sort = $params['sort'] ?? null;

        if ($sort) {
            $arrSort = explode(',', $sort);
            $shouldSeparate = count($arrSort) - 1;
            $query .= ' ORDER BY';
            foreach ($arrSort as $value) {
                if ($value[0] === '-') {
                    $value = substr($value, 1);
                    $order = 'DESC';
                } else {
                    $order = 'ASC';
                }

                $query .= " ${value} ${order}";
                if ($shouldSeparate--) {
                    $query .= " ,";
                }
            }
            $request = $request->withAttribute('query', $query);
        }
        $response = $next($request, $response);
        return $response;
    }
}