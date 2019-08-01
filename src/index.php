<?php

use App\Base\DataBase;
use App\Models\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Container;

define('ROOT', __DIR__);
require_once (ROOT . '/../vendor/autoload.php');
$db = require_once (ROOT . '/Config/db.php');

$config = [
    'settings' => [
        'displayErrorDetails' => true,
//        'db' => $db,
    ],
];


//var_dump($db);
//die;

$app = new App();


$container = $app->getContainer();

$container['objectDataBase'] = function ($container) {

    $db = DataBase::getInstance();

    return $db;
};



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

    var_dump($request->getQueryParams());
    die;

    //$result = (new User($this->get('objectDataBase')))->getUsers();

    return $response->withJson($result);
});



$app->get('/users/{id}', function (Request $request, Response $response, $args) {

//    $params = $request->getQueryParams();
//
//    var_dump($args);
//    echo '<hr>';
//    var_dump($params);
//    die;

    $id = filter_var($args['id'], FILTER_VALIDATE_INT);
    $result = (new User($this->get('objectDataBase')))->getUser($id);

    return $response->withJson($result);
});

$app->run();
