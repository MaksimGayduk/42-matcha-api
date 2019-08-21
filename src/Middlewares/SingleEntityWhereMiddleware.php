<?php


namespace App\Middlewares;


use App\Base\SqlQueryBuilder;

class SingleEntityWhereMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $arrRout = (explode('/',  $request->getUri()->getPath()));
        $id = filter_var($arrRout[2], FILTER_VALIDATE_INT);

        $query = $request->getAttribute('query');
        $queryParams = ['id' => $id];
        $query .= SqlQueryBuilder::where($queryParams);

//        var_dump($query);
//        die;

        $request = $request->withAttribute('query', $query);
        $request = $request->withAttribute('queryParams', $queryParams);
        $response = $next($request, $response);

        return $response;
    }
}
