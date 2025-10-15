<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Estado inicial del modelo.
     * Datos de prueba genericos.
     * 
     * ? como consigo un sku (codigo de producto) realista?
     * ? como consigo un barcode (codigo de barras) realista?
     * ? como logro un costo de el precio mas un 5%?
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_name'  => $this->faker->unique()->word(),
            'product_desc'  => $this->faker->words(4, true),
            'sku'           => $this->faker->unique()->word(),    
            'barcode'       => $this->faker->word(),
            'price'         => $this->faker->randomFloat(2, 100, 5000),        
            'cost'          => $this->faker->randomFloat(2, 100, 5000),         
            'tax_rate'      => 0.21,  
            'is_active'     => true,
        ];
    }

    /**
     * Un producto real.
     * 
     * @param string $name
     * @param string $desc
     * @param string $sku
     * @param string $barcode
     * @param float $price
     * @param float $cost
     * @param App\Models\Brand $brand
     * @param App\Models\Category $category
     */
    public function realProduct(
        string $name,
        string $desc,
        string $sku,
        string $barcode,
        float $price,
        float $cost,
        Brand $brand,
        Category $category
    ): static
    {
       return $this->state(fn (array $attributes) => [
            'product_name'  => $name,
            'product_desc'  => $desc,
            'sku'           => $sku,    
            'barcode'       => $barcode,
            'price'         => $price,        
            'cost'          => $cost,
            'brand_id'      => $brand->id,
            'category_id'   => $category->id,
        ]);
    }
}
