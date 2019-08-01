<?php


namespace App\Base;


class BaseModel
{
    protected $db;

    public function __construct($objectDataBase)
    {
        $this->db = $objectDataBase;
    }
}