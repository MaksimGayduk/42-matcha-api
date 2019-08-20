<?php


namespace App\Middlewares;


class WhereMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $arrRout = (explode('/',  $request->getUri()->getPath()));
        $id = filter_var($arrRout[2], FILTER_VALIDATE_INT);

        $query = $request->getAttribute('query');
        $query .= " WHERE id=:id";
        $queryParams = ['id' => $id];

        $request = $request->withAttribute('query', $query);
        $request = $request->withAttribute('queryParams', $queryParams);
        $response = $next($request, $response);

        return $response;
    }
}