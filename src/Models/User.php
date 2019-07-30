<?php


namespace App\Models;

use App\Base\DataBase;

//$config = require_once ('../Config/db.php');



class User
{

    private $users = [
        [
            'id' => 1,
            'login' => 'anonymus',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'age' => 27
        ],
        [
            'id' => 2,
            'login' => 'slim',
            'firstName' => 'Vasia',
            'lastName' => 'Pupkin',
            'age' => 27
        ],
        [
            'id' => 3,
            'login' => 'yii',
            'firstName' => 'Jacky',
            'lastName' => 'Chan',
            'age' => 27
        ]
    ];

    public function getUsers()
    {
//        return $this->users;
        $config = require_once (ROOT . '/Config/db.php');
//var_dump($config);
//die();


        $db = DataBase::getInstance($config);

//        var_dump($db);
//        die;
        $res = $db->pdo->query("SELECT * FROM users")->fetchAll();
//        var_dump($res);
//        die;
        return $res;
    }

    public function getUser(int $id)
    {
        return array_filter($this->users, function ($user) use ($id) {
           return $user['id'] === $id;
        });
    }





}