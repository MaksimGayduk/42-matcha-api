<?php

namespace App\Middlewares;

class BaseMiddleware
{
    protected function checkExistKey($params, $paramsCmpKeys, $notValidParams)
    {
        foreach ($params as $key => $value) {
            if(!array_key_exists($key , $paramsCmpKeys))
                $notValidParams[] = $key;
        }
        return $notValidParams;
    }
}
