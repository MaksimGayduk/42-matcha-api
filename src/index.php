<?php

use App\Base\DataBase;
use App\Models\User;
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

define('ROOT', __DIR__);
require_once (ROOT . '/../vendor/autoload.php');
$configDb = require_once (ROOT . '/Config/db.php');
$entities = require_once (ROOT . '/Config/entities.php');

//var_dump(array_keys($entities));
//var_dump(array_column($entities, 'users'));
//die;

$config = [
    'settings' => [
        'displayErrorDetails' => true,
        'configDb' => $configDb,
    ],
];


$app = new App($config);


$container = $app->getContainer();
$container['objectDataBase'] = function ($container) {

    $configDb = $container->get('settings')['configDb'];
    $db = DataBase::getInstance($configDb);

    return $db;
};


$app->get('/{rout}', function (Request $request, Response $response, $args)
{
    $db = $this->get('objectDataBase');

    $query = $request->getAttribute('query');
    $queryParams = $request->getAttribute('queryParams');
    $result = $db->executeQuery($query, $queryParams);

    return $response->withJson($result) ;
})->add(new OutputFormatterMiddleware())->add(new SortMiddleware())->add(new FilterMiddleware())->add(new SelectMiddleware())->add(new ValidatorQueryParamsKeyMiddleware())->add(new ValidatorQueryParamsNameMiddleware());


$app->get('/{rout}/{id}', function (Request $request, Response $response, $args)
{
    $db = $this->get('objectDataBase');

    $query = $request->getAttribute('query');
    $queryParams = $request->getAttribute('queryParams');
    $result = $db->executeQuery($query, $queryParams);

    return $response->withJson($result);
})->add(new OutputFormatterMiddleware())->add(new WhereMiddleware())->add(new SelectMiddleware());


$app->run();
