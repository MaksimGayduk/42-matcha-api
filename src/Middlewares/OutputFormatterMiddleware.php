<?php

namespace App\Middlewares;

class OutputFormatterMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $response = $next($request, $response);

        $mainEntityName = $request->getAttribute('entity');
        $mainEntities = json_decode($response->getBody()->__toString(), true);
        $data = $this->prepareResponse($mainEntities, $mainEntityName);

        return $response->withJson($data);
    }

    private function prepareResponse(array $mainEntities, string $mainEntityName): array
    {
        foreach ($mainEntities as $mainEntity) {

            $data['date'][] = $this->prepareData($mainEntity, $mainEntityName);

            if (!isset($mainEntity['includes'])) {
                continue;
            }
            foreach ($mainEntity['includes'] as $includesEntityName => $includesEntities) {
                foreach ($includesEntities as $includesEntity) {
                    $data['includes'][] = $this->prepareIncludes($includesEntity, $includesEntityName);
                }
            }
        }

        return $data ?? [];
    }

    private function prepareData(array $mainEntity, string $mainEntityName): array
    {
        $res = [
            "type" => $mainEntityName,
            "id" => $mainEntity['id'],
            'attributes' =>
                array_filter($mainEntity, function($key) {
                    return ($key != 'id' && $key != 'includes');
                }, ARRAY_FILTER_USE_KEY),
            'relationships' => $this->formatRelationships($mainEntity),
            'links' => [
                'self' => $mainEntityName . '/' . $mainEntity['id']
            ]
        ];

        return $res;
    }

    private function formatRelationships(array $mainEntity): array
    {
        $res = [];
        if (!isset($mainEntity['includes'])) {
            return $res;
        }
        foreach ($mainEntity['includes'] as $includesEntityName => $includesEntities) {
            foreach ($includesEntities as $includesEntity) {
                $res[$includesEntityName]['data'][] = [
                    'id' => $includesEntity['id'],
                    'type' => $includesEntityName,
                ];
            }
        }

        return $res;
    }

    private function prepareIncludes(array $includesEntity, string $includesEntityName): array
    {
        $res = [
            "type" => $includesEntityName,
            "id" => $includesEntity['id'],
            'attributes' =>
                array_filter($includesEntity, function ($key) {
                    return $key != 'id';
                    }, ARRAY_FILTER_USE_KEY),
            'relationships' => $this->formatRelationships($includesEntity),
            'links' => [
                'self' => $includesEntityName . '/' . $includesEntity['id']
            ]
        ];

        return $res;
    }
}
