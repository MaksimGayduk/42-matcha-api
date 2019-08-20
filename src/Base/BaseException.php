<?php

namespace App\Base;

use Exception;

class BaseException extends Exception
{
    private $status;

    public function __construct(string $message = "", int $code = 0, string $status = "")
    {
        parent::__construct($message, $code);
        $this->status = $code . ' ' . $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
