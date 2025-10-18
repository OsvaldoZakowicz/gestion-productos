<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    // tamaño de paginacion
    public const PAGINATE = 10;

    // atributos asignables
    protected $fillable = ['brand_name'];

    /**
     * Una marca tiene muchos productos
     * Brand HasMany Product
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }

}
