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
            $query = $this->prepareSortQuery($query, $sort);
            $request = $request->withAttribute('query', $query);
        }

        $response = $next($request, $response);
        return $response;
    }

    private function prepareSortQuery(string $query, string $sort): string
    {
        $arrColumnsSort = explode(',', $sort);
        $shouldSeparate = count($arrColumnsSort) - 1;
        $query .= ' ORDER BY';
        foreach ($arrColumnsSort as $column) {
            $order = $this->getOrder($column);
            $column = ($order == 'DESC') ? substr($column, 1) : $column;
            $query .= " {$column} {$order}";
            if ($shouldSeparate--) {
                $query .= ",";
            }
        }

        return $query;
    }

    private function getOrder(string $column): string
    {
        return $column[0] === '-' ? 'DESC' : 'ASC';
    }
}
