<?php

namespace App\Exceptions\Category;

use App\Exceptions\BaseException;

class CategoryAlreadyInUseException extends BaseException
{
    protected string $redirect_route = 'categories.index';

    public function __construct(string $message = 'la categoria se está usando en productos, no puede borrarse.')
    {
        parent::__construct($message);
    }
}
