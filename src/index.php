<?php

use App\Base\DataBase;
use App\Models\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Container;

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





//$emptyResponse = function ($request, $response, $next)
//{
//    $response = $next($request, $response);
//
//    if (empty($response->getBody()->getContents())) {
//        $response = $response->withStatus(404);
//    }
//
//    return $response;
//};



$sortMiddleware = function(Request $request, Response $response, $next) {

    $params = $request->getQueryParams();
    $query = $request->getAttribute('query');
    $sort = $params['sort'] ?? null;

    if ($sort) {
        $arrSort = explode(',', $sort);
        $shouldSeparate = count($arrSort) - 1;
        $query .= ' ORDER BY';
        foreach ($arrSort as $value) {
            if($value[0] === '-'){
                $value = substr($value, 1);
                $order = 'DESC';
            }
            else{
                $order = 'ASC';
            }

            $query .= " ${value} ${order}";
            if ($shouldSeparate--) {
                $query .= " ,";
            }
        }

        $request = $request->withAttribute('query', $query);
    }

    $response = $next($request, $response);


    return $response;
};



$selectMiddleware = function (Request $request, Response $response, $next) {

    $arrRout = (explode('/',  $request->getUri()->getPath()));
    $tableName = $arrRout[1];
    $query = 'SELECT * FROM ' . $tableName;

    $request = $request->withAttribute('query', $query);

    $response = $next($request, $response);


    return $response;
};


$filterMiddleware = function (Request $request, Response $response, $next) {

    $arrRout = (explode('/',  $request->getUri()->getPath()));
    if(!empty($arrRout[2]) && empty($request->getQueryParams())){ //&& empty($request->getQueryParams()) // Если добавить второе условие, будет поиск по $request->getQueryParams() который пришел
        $id = filter_var($arrRout[2], FILTER_VALIDATE_INT);
        $arrSetQueryParams['filters'] = ['id' => $id];

        $request = $request->withQueryParams($arrSetQueryParams);
    }

    $params = $request->getQueryParams();
    $query = $request->getAttribute('query');
    $filters = $params['filters'] ?? null;


    if ($filters) {
        $shouldSeparate = count($filters) - 1;
        $query .= ' WHERE';
        foreach ($filters as $key => $value) {
            $query .= " ${key}=:${key}";
            if ($shouldSeparate--) {
                $query .= " AND";
            }
        }

        $queryParams = $request->getAttribute('queryParams') ?? [];
        $queryParams = array_merge($queryParams, $filters);

        $request = $request->withAttribute('query', $query);
        $request = $request->withAttribute('queryParams', $queryParams);
    }

    $response = $next($request, $response);


    return $response;
};


$app->get('/{rout}[/{id}]', function (Request $request, Response $response, $args) {

    $modelUser = (new User($this->get('objectDataBase')));
    $modelUser->fetchQuery($request);
    $result = $modelUser->getUsers();

    return $response->withJson($result);

})->add($sortMiddleware)->add($filterMiddleware)->add($selectMiddleware);


//$app->get('/{rout}/{id}', function (Request $request, Response $response, $args) {
//
//
//    $id = filter_var($args['id'], FILTER_VALIDATE_INT);
//
//
////    var_dump($request->getQueryParams());
////    die;
//
//    $modelUser = (new User($this->get('objectDataBase')));
//    $modelUser->fetchQuery($request);
//    $result = $modelUser->getUser();
//
//    return $response->withJson($result);
//})->add($filterMiddleware)->add($selectMiddleware);



$app->run();
