<?php

use App\Base\DataBase;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use App\Middlewares\FilterMiddleware;
use App\Middlewares\SortMiddleware;
use App\Middlewares\SelectMiddleware;
use App\Middlewares\ValidatorQueryParamsKeyMiddleware;
use App\Middlewares\ValidatorQueryParamsNameMiddleware;
use App\Middlewares\WhereMiddleware;
use App\Middlewares\OutputFormatterMiddleware;
use App\Middlewares\ValidatorEntityMiddleware;
use App\Middlewares\IncludeMiddleware;

define('ROOT', __DIR__);
require_once (ROOT . '/../vendor/autoload.php');
$configDb = require_once (ROOT . '/Config/db.php');

//phpinfo();
//die;

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
    ->add(new ValidatorQueryParamsKeyMiddleware())
    ->add(new ValidatorQueryParamsNameMiddleware())
    ->add(new ValidatorEntityMiddleware());


$app->get('/{entity}/{id}', function (Request $request, Response $response, $args)
{
    $db = $this->get('objectDataBase');

    $query = $request->getAttribute('query');
    $queryParams = $request->getAttribute('queryParams');
    $result = $db->executeQuery($query, $queryParams);



    return $response->withJson($result);
})
    ->add(new OutputFormatterMiddleware())
    ->add(new WhereMiddleware())
    ->add(new SelectMiddleware())
    ->add(new ValidatorEntityMiddleware());

$app->run();

