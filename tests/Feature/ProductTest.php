<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Existe el modelo Product
     */
    public function test_product_model_exists(): void
    {
        $this->assertTrue(
            class_exists('App\\Models\\Product'),
            'El modelo Product no existe'
        );
    }

    /**
     * Existe la tabla products
     */
    public function test_products_table_exists(): void
    {
        $this->assertTrue(
            Schema::hasTable('products'),
            'La tabla products no existe'
        );
    }

    /**
     * Verifica que el modelo Product tenga sus atributos
     * asignables de forma masiva
     */
    public function test_product_model_has_fillable_attributes(): void
    {
        $product = new \App\Models\Product;

        // atributos que el modelo Product puede asignar masivamente
        $expected_fillable = [
            'product_name',
            'product_desc',
            'sku',
            'barcode',
            'price',
            'cost',
            'tax_rate',
            'is_active',
            'brand_id',
            'category_id'
        ];

        $this->assertEquals(
            $expected_fillable,
            $product->getFillable(), // atributos que el modelo actualmente tiene
            'Los atributos del modelo no son los esperados'
        );
    }

    /**
     * Verifica que la tabla products tenga las siguientes
     * columnas
     */
    public function test_products_table_has_expected_columns(): void
    {
        $expected_columns = [
            'id',
            'product_name',
            'product_desc',
            'sku',
            'barcode',
            'price',
            'cost',
            'tax_rate',
            'is_active',
            'brand_id',
            'category_id',
            'created_at',
            'updated_at'
        ];

        $this->assertTrue(
            Schema::hasColumns('products', $expected_columns),
            'Las columnas de la tabla no son las esperadas'
        );
    }

    /**
     * Verifica que la tabla products tenga la clave foranea
     * fk brand_id para la tabla brands
     */
    public function test_products_table_has_brand_id_column(): void
    {
        $this->assertTrue(
            Schema::hasColumn('products', 'brand_id'),
            'La tabla products no tiene la columna fk a la tabla brands'
        );
    }

    /**
     * Verifica que el modelo Product tenga el metodo brand()
     * de tipo BelongsTo para la relacion Product belongs to Brand.
     */
    public function test_product_belongs_to_brand(): void
    {
        $product = new \App\Models\Product;

        $this->assertTrue(
            method_exists($product, 'brand'),
            'El modelo Product no tiene el metodo brand()'
        );

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $product->brand(),
            'la relacion brand no es del tipo belongsto'
        );

        $relation = $product->brand();

        $this->assertInstanceOf(
            \App\Models\Brand::class,
            $relation->getRelated(),
            'El metodo brand no esta apuntando al modelo Brand',
        );
    }

    /**
     * Verifica que la tabla products tenga la clave foranea
     * fk brand_id para la tabla brands
     */
    public function test_products_table_has_category_id_column(): void
    {
        $this->assertTrue(
            Schema::hasColumn('products', 'category_id'),
            'La tabla products no tiene la columna fk a la tabla categories'
        );
    }

    /**
     * Verifica que el modelo Product tenga el metodo category()
     * de tipo BelongsTo para la relacion Product belongs to Category.
     */
    public function test_product_belongs_to_category(): void
    {
        $product = new \App\Models\Product;

        $this->assertTrue(
            method_exists($product, 'category'),
            'El modelo Product no tiene el metodo category()'
        );

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $product->category(),
            'la relacion category no es del tipo belongsto'
        );

        $relation = $product->category();

        $this->assertInstanceOf(
            \App\Models\Category::class,
            $relation->getRelated(),
            'El metodo category no esta apuntando al modelo Category',
        );
    }

}
