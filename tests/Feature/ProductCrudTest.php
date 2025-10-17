<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Existe la ruta para la lista de productos
     * y retorna 200 Ok
     */
    public function test_route_index_product_exists(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('product.index');
    }

    
    /**
     * Existe la ruta para el formulario de creacion
     * del producto y retorna 200 OK
     */
    public function test_route_create_product_exists(): void
    {
        // get() recibe una URI, con route(name) obtengo URI a traves del nombre de ruta
        $response = $this->get(route('products.create'));

        $response->assertStatus(200);               //OK con la ruta
        $response->assertViewIs('product.form');    // espero esta vista
        $response->assertSeeHtml('form');           // espero html de un form como minimo
    }

    /**
     * Existe la ruta para almacenar un producto desde el
     * formulario de creacion y retorna 302 (redirect a index on success)
     * 
     * NOTA: Test quisquilloso:
     * - Debemos omitir cualquier tipo de middleware
     * - Es necesario pasar validaciones, por ello, es necesario enviar
     * los correctos datos de prueba
     * - Se usa create() para persistir lo que debe existir en BD
     * - Se usa makeOne() para crear, SIN persistir, una instancia de producto
     */
    public function test_route_store_product_exists(): void
    {
        $this->withoutMiddleware();

        $brand = Brand::factory()
            ->create();

        $category = Category::factory()
            ->create();

        // lo que quiero guardar debe presentarse como array para el post
        $product = Product::factory()
            ->withBrandAndCategory($brand, $category)
                ->makeOne()
                ->toArray();

        $response = $this->post(route('products.store'), [
            'product_name'  => $product['product_name'],
            'product_desc'  => $product['product_desc'],
            'sku'           => $product['sku'],
            'barcode'       => $product['barcode'],
            'price'         => (string) $product['price'],
            'cost'          => (string) $product['cost'],
            'brand_id'      => (string) $product['brand_id'],
            'category_id'   => (string) $product['category_id']
        ]);

        $response->assertStatus(302);                           // ok redireccion
        $response->assertRedirect(route('products.index'));     // redireccion a index
        $response->assertValid();                               // sin errores de validacion
        $response->assertSessionHasNoErrors();                  // sin errores en sesion
    }

    /**
     * Crear un producto.
     */
    public function test_create_product(): void
    {
        $brand = Brand::factory()
            ->create();  // persiste en BD

        $category = Category::factory()
            ->create();  // persiste en BD

        $product_data = Product::factory()
            ->withBrandAndCategory($brand, $category)
            ->makeOne(); // en memoria

        $product = Product::create($product_data->toArray());

        $this->assertDatabaseHas('products', [
            'product_name' => $product->product_name,
            'brand_id'     => $brand->id,
            'category_id'  => $category->id
        ]);
    }

    /**
     * Existe la ruta para el formulario de edicion
     * del producto.
     * - la ruta retorna un codigo 200 ok
     * - la vista devuelta coincide con la esperada
     * - el html de la vista contiene un formulario
     */
    public function test_route_edit_product_exists(): void
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        // el producto que quiero editar debe existir
        $product = Product::factory()->withBrandAndCategory($brand, $category)->create();

        // get ruta edit con el producto como parametro
        $response = $this->get(route('products.edit', ['product' => $product->id]));

        $response->assertStatus(200);               //ok ruta
        $response->assertViewIs('product.form');    // ok vista
        $response->assertSeeHtml('form');           // ok form
    }
}
