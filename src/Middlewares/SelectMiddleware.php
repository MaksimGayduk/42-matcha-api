<?php

namespace App\Middlewares;

class SelectMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $arrRout = (explode('/',  $request->getUri()->getPath()));
        $tableName = $arrRout[1];
        $modelName = ucfirst(substr($arrRout[1], 0,-1));

//        var_dump($modelName);
//        die;

        $query = 'SELECT * FROM ' . $tableName;

        $request = $request->withAttribute('query', $query);
        $request = $request->withAttribute('tableName', $tableName);
//        $request = $request->withAttribute('modelName', $modelName);
        $response = $next($request, $response);

        return $response;
    }
}
