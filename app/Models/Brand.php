<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    // tamaño de paginacion
    public const PAGINATE = 10;

    // atributos asignables
    protected $fillable = ['brand_name'];

}
