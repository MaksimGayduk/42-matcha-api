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



    public function getUser(int $id)
    {
        $query = "SELECT * FROM users WHERE id=:id";
        $queryParams = ['id' => $id];

        $res = $this->db->pdo->prepare($query);
        $res->execute($queryParams);
        return $res->fetchAll();

        return $res;
    }

}