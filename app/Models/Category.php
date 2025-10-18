<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    
    // tamaño de paginacion
    public const PAGINATE = 10;

    // atributos asignables
    protected $fillable = ['category_name', 'category_desc'];

    /**
     * Una categoria tiene muchos productos
     * Category HasMany Product
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
