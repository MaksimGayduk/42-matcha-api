<?php

namespace App\Helpers;

use Slim\Http\Request;

class QueryHelper
{

    public static function getMainEntityName(Request $request): string
    {
     return (explode('/',  $request->getUri()->getPath()))[1];
    }

}
