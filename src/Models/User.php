<?php


namespace App\Models;

use App\Base\BaseModel;

class User extends BaseModel
{


    public function getUsers()
    {

        $res = $this->db->pdo->prepare($this->query);
        $res->execute($this->queryParams);
        return $res->fetchAll();
    }

//    public function getUser(int $id)
//    {
//
//        $res = $this->db->pdo->query("SELECT * FROM users WHERE id=$id")->fetchAll();
//
//        $res = $this->db->pdo->prepare($this->query);
//        $res->execute($this->queryParams);
//        return $res->fetchAll();
//
//        return $res;
//    }

}