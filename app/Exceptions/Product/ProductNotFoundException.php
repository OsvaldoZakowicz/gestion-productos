<?php

namespace App\Exceptions\Product;

use App\Exceptions\BaseException;

class ProductNotFoundException extends BaseException
{
    protected string $redirect_route = 'products.index';

    public function __construct(string $message = 'el producto no fue encontrado.')
    {
        parent::__construct($message);
    }
}
