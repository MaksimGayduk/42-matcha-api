<?php


namespace App\Models;


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
        return $this->users;
    }

    public function getUser(int $id)
    {
        return array_filter($this->users, function ($user) use ($id) {
           return $user['id'] === $id;
        });
    }
}