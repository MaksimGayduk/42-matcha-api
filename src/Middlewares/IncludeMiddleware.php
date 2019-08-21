<?php

namespace App\Middlewares;

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

        $mainEntityName = $request->getAttribute('entity');
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

                $query = "
                        SELECT * FROM {$includesEntityName} 
                        WHERE {$mainEntityName}_id=:{$mainEntityName}_id
                        ";

                $queryParams = ["{$mainEntityName}_id" => $mainEntity['id']];
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
