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
$container['objectDataBase'] = function ($container) {

    $configDb = $container->get('settings')['configDb'];
    $db = DataBase::getInstance($configDb);

    return $db;
};


$app->get('/{rout}', function (Request $request, Response $response, $args)
{
    $modelUser = (new User($this->get('objectDataBase')));
    $modelUser->fetchQuery($request);
    $result = $modelUser->getUsers();

    return $response->withJson($result);

})->add(new SortMiddleware())->add(new FilterMiddleware())->add(new SelectMiddleware())->add(new ValidatorQueryParamsKeyMiddleware())->add(new ValidatorQueryParamsNameMiddleware());


$app->get('/{rout}/{id}', function (Request $request, Response $response, $args)
{
    $id = filter_var($args['id'], FILTER_VALIDATE_INT);
    $modelUser = (new User($this->get('objectDataBase')));
    $result = $modelUser->getUser($id);

    return $response->withJson($result);
});


$app->run();
