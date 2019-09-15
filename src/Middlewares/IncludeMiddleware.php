<?php

namespace App\Middlewares;

use App\Base\SqlQueryBuilder;
use App\Helpers\QueryHelper;

class IncludeMiddleware
{
    private $db;

    public function __construct($objectDataBase)
    {
        $this->db = $objectDataBase;
    }

    public function __invoke($request, $response, $next)
    {
        $response = $next($request, $response);

        $params = $request->getQueryParams();
        $includes = $params['includes'] ?? null;

        if (!$includes) {
            return $response;
        }

        $mainEntityName = QueryHelper::getMainEntityName($request->getUri()->getPath());

        $data = json_decode($response->getBody()->__toString(), true);
        $result = $this->prepareResponse($includes, $mainEntityName, $data);

        return $response->withJson($result);
    }

    private function prepareResponse(string $includes, string $mainEntityName, array $data): array
    {
        $includesEntityNames = explode(',', $includes);

        $result = [];
        foreach ($data as $mainEntity) {
            foreach ($includesEntityNames as $includesEntityName) {

                $queryParams = ["{$mainEntityName}_id" => $mainEntity['id']];

                $query = SqlQueryBuilder::select($includesEntityName);
                $query .= SqlQueryBuilder::where($queryParams);

                $res = $this->db->executeQuery($query, $queryParams);
                if ($res) {
                    $mainEntity['includes'][$includesEntityName] = $res;
                }
            }
            $result[] = $mainEntity;
        }

        return $result;
    }
}
