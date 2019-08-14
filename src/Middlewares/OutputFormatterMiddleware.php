<?php


namespace App\Middlewares;


class OutputFormatterMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $response = $next($request, $response);

        $entity = $request->getAttribute('entity');
        $res = json_decode($response->getBody()->__toString(), true);
        $data = $this->prepareData($res, $entity);

        return $response->withJson($data);
    }

    private function prepareData($res, $entity)
    {
        foreach ($res as $item) {
            $data['data'][] = [
                "type" => $entity,
                "id" => $item['id'],
                'attributes' =>
                    array_filter($item, function($key) {
                        return $key != 'id';
                    }, ARRAY_FILTER_USE_KEY),
                'links' => [
                    'self' => $entity . '/' . $item['id']
                ]
            ];
        }

        return $data ?? [];
    }
}