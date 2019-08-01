<?php


namespace App\Models;

use App\Base\BaseModel;


class User extends BaseModel
{


    public function getUsers()
    {

        $res = $this->db->pdo->query("SELECT * FROM users")->fetchAll();

        return $res;
    }

    public function getUser(int $id)
    {

        $res = $this->db->pdo->query("SELECT * FROM users WHERE id=$id")->fetchAll();

        return $res;
    }




}