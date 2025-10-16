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

        $response->assertStatus(200); //OK con la ruta
        $response->assertViewIs('product.form'); // espero esta vista
        $response->assertSeeHtml('form'); // espero html de un form como minimo
    }

    /**
     * Existe la ruta para almacenar un producto desde el
     * formulario de creacion y retorna 302 (redirect a index on success)
     */
    public function test_route_store_product_exists(): void
    {
        $this->withoutMiddleware();

        $response = $this->post(route('products.store'), []);

        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        $response->assertSessionHasNoErrors();
    }

    /**
     * Crear un producto usando un servicio.
     */
    public function test_create_product(): void
    {
        $brand = Brand::factory()->create();        // persiste en BD
        $category = Category::factory()->create();  // persiste en BD

        $product_data = Product::factory()->withBrandAndCategory($brand, $category)->makeOne(); // en memoria

        $product = Product::create($product_data->toArray());

        $this->assertDatabaseHas('products', [
            'product_name' => $product->product_name,
            'brand_id'     => $brand->id,
            'category_id'  => $category->id
        ]);
    }

    // todo:
    // test de ruta edit y update
    // test de update
    // test de delete
}
