<?php

namespace App\Middlewares;

class SelectMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $arrRout = (explode('/',  $request->getUri()->getPath()));
        $tableName = $arrRout[1];

        $query = 'SELECT * FROM ' . $tableName;

        $request = $request->withAttribute('query', $query);
        $request = $request->withAttribute('tableName', $tableName);
        $response = $next($request, $response);

        return $response;
    }
}
