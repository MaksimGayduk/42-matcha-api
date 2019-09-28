<?php

use App\Base\DataBase;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use App\Middlewares\FilterMiddleware;
use App\Middlewares\SortMiddleware;
use App\Middlewares\SelectMiddleware;
use App\Middlewares\QueryParamsKeyValidatorMiddleware;
use App\Middlewares\QueryParamsNameValidatorMiddleware;
use App\Middlewares\SingleEntityWhereMiddleware;
use App\Middlewares\OutputFormatterMiddleware;
use App\Middlewares\EntityValidatorMiddleware;
use App\Middlewares\IncludeMiddleware;
use App\Middlewares\QueryParamsValueValidatorMiddleware;
use App\Middlewares\FieldsEntityValueValidatorMiddleware;
use App\Middlewares\FieldsEntityNameValidatorModdleware;
use App\Base\SqlQueryBuilder;

define('ROOT', __DIR__);
require_once (ROOT . '/../vendor/autoload.php');
$configDb = require_once (ROOT . '/Config/db.php');

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'configDb' => $configDb,
    ],
];

$app = new App($config);

$container = $app->getContainer();

$container['errorHandler'] = function () {
    return function ($request, $response, $exception) {
        $errors = [
            "errors" => [
                "status" => $exception->getStatus(),
                "title" => $exception->getMessage(),
            ]
        ];

        return $response->withJson($errors, $exception->getCode());
    };
};

$container['objectDataBase'] = function ($container) {

    $configDb = $container->get('settings')['configDb'];
    $db = DataBase::getInstance($configDb);

    return $db;
};



$app->get('/{entity}', function (Request $request, Response $response, $args)
{
    $db = $this->get('objectDataBase');

    $query = $request->getAttribute('query');
    $queryParams = $request->getAttribute('queryParams');
    $result = $db->executeQuery($query, $queryParams);

    return $response->withJson($result) ;
})
    ->add(new IncludeMiddleware($container['objectDataBase']))
    ->add(new OutputFormatterMiddleware())
    ->add(new SortMiddleware())
    ->add(new FilterMiddleware())
    ->add(new SelectMiddleware())
    ->add(new QueryParamsValueValidatorMiddleware())
    ->add(new QueryParamsKeyValidatorMiddleware())
    ->add(new QueryParamsNameValidatorMiddleware())
    ->add(new EntityValidatorMiddleware());



$app->get('/{entity}/{id}', function (Request $request, Response $response, $args)
{
    $db = $this->get('objectDataBase');

    $query = $request->getAttribute('query');
    $queryParams = $request->getAttribute('queryParams');
    $result = $db->executeQuery($query, $queryParams);

    return $response->withJson($result);
})
    ->add(new IncludeMiddleware($container['objectDataBase']))
    ->add(new OutputFormatterMiddleware())
    ->add(new SingleEntityWhereMiddleware())
    ->add(new SelectMiddleware())
    ->add(new QueryParamsKeyValidatorMiddleware())
    ->add(new QueryParamsNameValidatorMiddleware())
    ->add(new EntityValidatorMiddleware());



$app->post('/{entity}', function (Request $request, Response $response, $args)
{
    $db = $this->get('objectDataBase');

    $body = json_decode($request->getBody()->__toString(), true);
    $mainEntityName = $body['data']['type'];
    $bodyAttributes = $body['data']['attributes'];
    $query = SqlQueryBuilder::insert($mainEntityName, $bodyAttributes);

    $db->executeQuery($query, $bodyAttributes);
    $result = $db->getNewRecord($mainEntityName);

    return $response->withJson($result);
})
    ->add(new OutputFormatterMiddleware())
    ->add(new FieldsEntityValueValidatorMiddleware($container['objectDataBase']))
    ->add(new FieldsEntityNameValidatorModdleware())
    ->add(new EntityValidatorMiddleware());

$app->run();
