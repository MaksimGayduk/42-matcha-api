<?php

namespace App\Middlewares;

use App\Base\SqlQueryBuilder;
use App\Helpers\QueryHelper;

class SelectMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $mainEntityName = QueryHelper::getMainEntityName($request);

        $query = SqlQueryBuilder::select($mainEntityName);

        $request = $request->withAttribute('query', $query);
        $response = $next($request, $response);

        return $response;
    }
}
