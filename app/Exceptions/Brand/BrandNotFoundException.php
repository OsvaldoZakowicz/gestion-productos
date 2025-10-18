<?php

namespace App\Exceptions\Brand;

use App\Exceptions\BaseException;

class BrandNotFoundException extends BaseException
{
    protected string $redirect_route = 'brands.index';

    public function __construct(string $message = 'la marca no fue encontrada.')
    {
        parent::__construct($message);
    }
}
