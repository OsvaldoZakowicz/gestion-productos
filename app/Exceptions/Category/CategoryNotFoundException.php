<?php

namespace App\Exceptions\Category;

use App\Exceptions\BaseException;

class CategoryNotFoundException extends BaseException
{
    protected string $redirect_route = 'categories.index';

    public function __construct(string $message = 'la categoria no fue encontrada.')
    {
        parent::__construct($message);
    }
}
