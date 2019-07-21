<?php

use App\Models\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

define('ROOT', __DIR__);
require_once (ROOT . '/../vendor/autoload.php');

$config = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new App($config);

$emptyResponse = function ($request, $response, $next)
{
    $response = $next($request, $response);

    if (empty($response->getBody()->getContents())) {
        $response = $response->withStatus(404);
    }

    return $response;
};

$app->add($emptyResponse);

$app->get('/users', function (Request $request, Response $response) {

    $result = (new User())->getUsers();

    return $response->withJson($result);
});

$app->get('/users/{id}', function (Request $request, Response $response, $args) {

    $id = filter_var($args['id'], FILTER_VALIDATE_INT);
    $result = (new User())->getUser($id);

    return $response->withJson($result);
});

$app->run();
