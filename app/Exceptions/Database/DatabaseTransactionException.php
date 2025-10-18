<?php

namespace App\Exceptions\Database;

use App\Exceptions\BaseException;

class DatabaseTransactionException extends BaseException
{
    protected string $log_level = 'critical';

    public function __construct(string $message = 'mensaje', string $redirect_route = '/')
    {
        parent::__construct($message, $redirect_route);
    }
}
