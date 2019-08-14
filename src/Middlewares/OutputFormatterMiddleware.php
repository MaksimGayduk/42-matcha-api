<?php


namespace App\Middlewares;


class OutputFormatterMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $response = $next($request, $response);

        $tableName = $request->getAttribute('tableName');
        $res = json_decode($response->getBody()->__toString(), true);
        $data = $this->prepareData($res, $tableName);

        return $response->withJson($data);
    }

    private function prepareData($res, $tableName)
    {
        foreach ($res as $item) {
            $data['data'][] = [
                "type" => $tableName,
                "id" => $item['id'],
                'attributes' =>
                    array_filter($item, function($key) {
                        return $key != 'id';
                    }, ARRAY_FILTER_USE_KEY),
                'links' => [
                    'self' => $tableName . '/' . $item['id']
                ]
            ];
        }
        return $data;
    }
}