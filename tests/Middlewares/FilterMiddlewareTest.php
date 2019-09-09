<?php

use Slim\App;
use App\Middlewares\FilterMiddleware;
use PHPUnit\Framework\TestCase;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Http\Headers;
use Slim\Http\RequestBody;
use Slim\Http\Stream;

class FilterMiddlewareTest extends TestCase
{
    public function addTrueValueProvider()
    {
        return [
            'true 1' => [
                ['filters' => ['id' => '1']],
                'SELECT * FROM users',
                ['query' => 'SELECT * FROM users WHERE id=:id', 'queryParams' => ['id' => '1']]
            ],
            'true 2' => [
                ['filters' => ['id' => '1,2']],
                'SELECT * FROM users',
                ['query' => 'SELECT * FROM users WHERE id IN (:id_1,:id_2)', 'queryParams' => ['id_1' => '1', 'id_2' => '2']]
            ],
            'true 3' => [
                ['filters' => ['age' => '1']],
                'SELECT * FROM users',
                ['query' => 'SELECT * FROM users WHERE age=:age', 'queryParams' => ['age' => '1']]
            ],
            'true 4' => [
                ['filters' => ['users_id' => '1,2,3']],
                'SELECT * FROM coments',
                ['query' => 'SELECT * FROM coments WHERE users_id IN (:users_id_1,:users_id_2,:users_id_3)', 'queryParams' => ['users_id_1' => '1', 'users_id_2' => '2', 'users_id_3' => '3']]
            ],
        ];
    }

    public function addFalseValueProvider()
    {
        return [
            'false 1' => [
                ['filters' => ['id' => '1']],
                'SELECT * FROM users',
                ['query' => 'SELECT * FROM users WHERE id=:id', 'queryParams' => []]
            ],
            'false 2' => [
                ['filters' => ['id' => '1,2']],
                'SELECT * FROM users',
                ['query' => 'SELECT * FROM users WHERE id IN (:id_1,:id_2)', 'queryParams' => ['id_1' => '1']]
            ],
            'false 3' => [
                ['filters' => ['age' => '1']],
                'SELECT * FROM users',
                ['query' => 'SELECT * FROM comments WHERE age=:age', 'queryParams' => ['age' => '1']]
            ],
            'false 4' => [
                ['filters' => ['users_id' => '1,2,3']],
                'SELECT * FROM coments',
                ['query' => 'SELECT * FROM coments WHERE users_id INN (:users_id_1,:users_id_2,:users_id_3)', 'queryParams' => ['users_id_1' => '1', 'users_id_2' => '2', 'users_id_3' => '3']]
            ],
        ];
    }

    private function createRequest(array $filters, string $baseQuery): Request
    {
        $request = new Request(
            'GET',
            new Uri('', ''),
            new Headers([]),
            [],
            [],
            new Stream(stream_context_create([])
            )
        );

        $request = $request->withQueryParams($filters); //['filters' => ['id' => '1']]
        $request = $request->withAttribute('query', $baseQuery); //'SELECT * FROM users'

        return $request;
    }

    /**
     * @dataProvider addTrueValueProvider
     */
    public function testCheckTrueValueMiddlewareResponse($filters, $baseQuery, $cmpQuery)
    {
        $request = $this->createRequest($filters, $baseQuery);
        $response = new Response();
        $middleware = new FilterMiddleware();

        /**
         * @var Request $request
         */
        $middlewareResponse = $middleware(
            $request,
            $response,
            function ($request, $response) {
                $params['query'] = $request->getAttribute('query');
                $params['queryParams'] = $request->getAttribute('queryParams');

                return $params;
            }
        );

        $this->assertEquals($cmpQuery, $middlewareResponse);
    }

    /**
     * @dataProvider addFalseValueProvider
     */
    public function testCheckFalseValueMiddlewareResponse($filters, $baseQuery, $cmpQuery)
    {
        $request = $this->createRequest($filters, $baseQuery);
        $response = new Response();
        $middleware = new FilterMiddleware();

        /**
         * @var Request $request
         */
        $middlewareResponse = $middleware(
            $request,
            $response,
            function ($request, $response) {
                $params['query'] = $request->getAttribute('query');
                $params['queryParams'] = $request->getAttribute('queryParams');

                return $params;
            }
        );

        $this->assertNotEquals($cmpQuery, $middlewareResponse);
    }
}
