<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

define('ROOT', __DIR__);
require_once (ROOT . '/../vendor/autoload.php');

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$conf = new \Slim\Container($configuration);

$app = new \Slim\App($conf);

$rout = function ($request, $response, $next) {
    // $response->getBody()->write('');
    $response = $next($request, $response);
    $response->getBody()->write(' [Middleware after!]');

    return $response;
};




$app->any('/{controllerName}[/{id}]', function (Request $request, Response $response, $args) {

    $method = $request->getMethod();
    $controllerName = $args['controllerName'];
    $id = $args['id'];
    $className = 'App\Controllers\\' . ucfirst($controllerName) . 'Controller';
    $actionName = 'action' . ucfirst(strtolower($method)) . ucfirst($controllerName);

    $object = new $className();
    $result = $object->$actionName($id);

    return $response->withJson($result);
    
})->add($rout);




$app->get('/', function ($request, $response, $args) {

    return $response->withJson('Api doc');

});

$app->run();



