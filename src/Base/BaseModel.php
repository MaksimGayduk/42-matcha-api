<?php


namespace App\Base;


use Slim\Http\Request;

class BaseModel
{
    protected $db;
    protected $query;
    protected $queryParams;

    public function __construct($objectDataBase)
    {
        $this->db = $objectDataBase;
    }



    public function createQuery(Request $request){
        $this->query = $request->getAttribute('query');
        $this->queryParams = $request->getAttribute('queryParams');

//        var_dump($this->queryParams);
//        die;
    }


}