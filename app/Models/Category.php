<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     // tamaño de paginacion
    public const PAGINATE = 10;

    // atributos asignables
    protected $fillable = ['category_name', 'category_desc'];
}
