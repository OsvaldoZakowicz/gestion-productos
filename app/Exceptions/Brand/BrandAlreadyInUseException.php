<?php

namespace App\Exceptions\Brand;

use App\Exceptions\BaseException;

class BrandAlreadyInUseException extends BaseException
{
    protected string $redirect_route = 'brands.index';

    public function __construct(string $message = 'la marca se está usando en productos, no puede borrarse.')
    {
        parent::__construct($message);
    }
}
