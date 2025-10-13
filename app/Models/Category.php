<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    // tamaño de paginacion
    public const PAGINATE = 10;

    // atributos asignables
    protected $fillable = ['category_name', 'category_desc'];
}
