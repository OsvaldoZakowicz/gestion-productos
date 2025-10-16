<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    // impueto de producto
    public const float TAX = 0.21;

    // tamaño de paginacion
    public const PAGINATE = 10;

    // atributos asignables
    protected $fillable = [
        'product_name',
        'product_desc',
        'sku',          // codigo unico
        'barcode',
        'price',        // precio de lista
        'cost',         // precio de costo
        'tax_rate',     // porcentaje de impuestos
        'is_active',
        'brand_id',
        'category_id'
    ];

    /**
     * Un producto pertenece a una marca
     * Product BelongsTo Brand
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    /**
     * Un producto pertenece a una categoria
     * Product BelongsTo Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
