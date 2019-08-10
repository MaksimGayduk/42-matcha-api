<?php

namespace App\Models;

use App\Base\BaseModel;

class User extends BaseModel
{
    protected static $fields = [
        'id' => 'int',
        'login' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'age' => 'int',
    ];

    public function getUsers()
    {
        $res = $this->db->executeQuery($this->query, $this->queryParams);
        return $res;
    }

    public function getUser(int $id)
    {
        $query = "SELECT * FROM users WHERE id=:id";
        $queryParams = ['id' => $id];

        $res = $this->db->executeQuery($query, $queryParams);
        return $res;

    }
}
