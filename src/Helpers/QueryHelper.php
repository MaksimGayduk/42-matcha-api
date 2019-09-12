<?php

namespace App\Helpers;

class QueryHelper
{
    public static function getMainEntityName(string $path): string
    {
     return (explode('/',  $path)[1]);
    }
}
