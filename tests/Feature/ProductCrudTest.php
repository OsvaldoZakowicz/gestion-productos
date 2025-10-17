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
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $products = Product::factory()
            ->withBrandAndCategory($brand, $category)
            ->count(3)
            ->create();

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('product.index');
        $response->assertViewHas('products');
        $products->each(fn($product) => $response->assertSee($product->product_name));
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
     * puede crear un producto a traves de la ruta store
     * - la ruta acepta peticiones post
     * - retorna codigo 302 (redireccion)
     * - redirige al index de productos
     * - no hay errores de validacion
     * - el producto se persiste correctamente en la base de datos
     * - las relaciones con brand y category son correctas
     */
    public function test_can_store_product_successfully(): void
    {
        // arrange: preparar datos necesarios
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $productData = Product::factory()
            ->withBrandAndCategory($brand, $category)
            ->makeOne()
            ->toArray();

        // act: ejecutar la accion
        $response = $this->post(route('products.store'), $productData);

        // assert: verificar resultados
        // verifica comportamiento http
        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        $response->assertValid();
        $response->assertSessionHasNoErrors();

        // verifica persistencia en base de datos
        $this->assertDatabaseHas('products', [
            'product_name' => $productData['product_name'],
            'product_desc' => $productData['product_desc'],
            'sku' => $productData['sku'],
            'barcode' => $productData['barcode'],
            'brand_id' => $brand->id,
            'category_id' => $category->id
        ]);

        // verifica que el producto existe y tiene las relaciones correctas
        $product = Product::where('sku', $productData['sku'])->first();
        $this->assertNotNull($product);
        $this->assertEquals($brand->id, $product->brand_id);
        $this->assertEquals($category->id, $product->category_id);
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
        $brand = Brand::factory()
            ->create();

        $category = Category::factory()
            ->create();

        // el producto que quiero editar debe existir
        $product = Product::factory()
            ->withBrandAndCategory($brand, $category)
            ->create();

        // get ruta edit con el producto como parametro
        $response = $this->get(route('products.edit', ['product' => $product->id]));

        $response->assertStatus(200);               //ok ruta
        $response->assertViewIs('product.form');    // ok vista
        $response->assertSeeHtml('form');           // ok form
    }

    /**
     * puede actualizar un producto a traves de la ruta update
     * - la ruta acepta peticiones put
     * - retorna codigo 302 (redireccion)
     * - redirige al index de productos
     * - no hay errores de validacion
     * - los cambios se persisten correctamente en la base de datos
     * - solo se actualizan los campos modificados
     * - las relaciones permanecen intactas o se actualizan correctamente
     */
    public function test_can_update_product_successfully(): void
    {
        // arrange: crear producto existente
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $product = Product::factory()
            ->withBrandAndCategory($brand, $category)
            ->create();

        // datos originales para comparacion
        $originalSku = $product->sku;
        $originalBarcode = $product->barcode;

        // datos de actualizacion
        $updateData = [
            'product_name' => 'producto actualizado',
            'product_desc' => 'descripcion actualizada',
            'sku' => $originalSku,
            'barcode' => $originalBarcode,
            'price' => '2500.50',
            'cost' => '1800.00',
            'brand_id' => $brand->id,
            'category_id' => $category->id
        ];

        // act: ejecutar actualizacion
        $response = $this->put(
            route('products.update', ['product' => $product->id]),
            $updateData
        );

        // assert: verificar comportamiento http
        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));
        $response->assertValid();
        $response->assertSessionHasNoErrors();

        // verificar persistencia en base de datos
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'product_name' => 'producto actualizado',
            'product_desc' => 'descripcion actualizada',
            'sku' => $originalSku,
            'brand_id' => $brand->id,
            'category_id' => $category->id
        ]);

        // verificar que el producto fue actualizado correctamente
        $product->refresh();
        $this->assertEquals('producto actualizado', $product->product_name);
        $this->assertEquals('descripcion actualizada', $product->product_desc);
        $this->assertEquals(2500.50, $product->price);
        $this->assertEquals(1800.00, $product->cost);

        // verificar que las relaciones se mantienen correctas
        $this->assertEquals($brand->id, $product->brand_id);
        $this->assertEquals($category->id, $product->category_id);
    }

    /**
     * puede eliminar un producto a traves de la ruta destroy
     * - la ruta acepta peticiones delete
     * - retorna codigo 302 (redireccion)
     * - redirige al index de productos
     * - el producto se elimina correctamente de la base de datos
     * - si usa soft deletes, verifica que el registro se marca como eliminado
     * - si usa hard delete, verifica que el registro ya no existe
     */
    public function test_can_destroy_product_successfully(): void
    {
        // arrange: crear producto existente
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $product = Product::factory()
            ->withBrandAndCategory($brand, $category)
            ->create();

        // guardar datos para verificacion posterior
        $productId = $product->id;

        // act: ejecutar eliminacion
        $response = $this->delete(route('products.destroy', $productId));

        // assert: verificar comportamiento http
        $response->assertStatus(302);
        $response->assertRedirect(route('products.index'));

        // verificar eliminacion en base de datos
        // opcion 1: si usamos soft deletes (recomendado)
        /* $this->assertSoftDeleted('products', [
            'id' => $productId
        ]); */

        // opcion 2: si usamos hard delete (descomentar si aplica)
        $this->assertDatabaseMissing('products', [
            'id' => $productId
        ]);

        // verificar que el producto ya no es accesible via eloquent
        $deletedProduct = Product::find($productId);
        $this->assertNull($deletedProduct);

        // si usas soft deletes, verificar que existe con withTrashed
        /* $trashedProduct = Product::withTrashed()->find($productId);
        $this->assertNotNull($trashedProduct);
        $this->assertNotNull($trashedProduct->deleted_at); */

        // verificar que no afecta a brand y category
        $this->assertDatabaseHas('brands', ['id' => $brand->id]);
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    /**
     * verifica que la ruta create existe y muestra correctamente el formulario
     * - la ruta retorna codigo 200 ok
     * - la vista devuelta es la esperada
     * - el formulario contiene los campos necesarios
     * - el metodo del formulario es post
     * - la accion del formulario apunta a la ruta store
     * - existen selects para brand y category
     */
    public function test_route_create_product_shows_form_correctly(): void
    {
        // arrange: crear datos necesarios para los selects
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        // act: solicitar formulario de creacion
        $response = $this->get(route('products.create'));

        // assert: verificar estructura del formulario
        $response->assertStatus(200);
        $response->assertViewIs('product.form');
        $response->assertSeeHtml('<form');

        // verificar campos del formulario
        $response->assertSee('product_name', false);
        $response->assertSee('product_desc', false);
        $response->assertSee('sku', false);
        $response->assertSee('barcode', false);
        $response->assertSee('price', false);
        $response->assertSee('cost', false);
        $response->assertSee('brand_id', false);
        $response->assertSee('category_id', false);

        // verificar que existen opciones en los selects
        $response->assertSee($brand->brand_name);
        $response->assertSee($category->category_name);

        // verificar metodo y accion del formulario
        $response->assertSee(route('products.store'), false);
    }

    /**
     * verifica que la ruta edit existe y muestra correctamente el formulario
     * - la ruta retorna codigo 200 ok
     * - la vista devuelta es la esperada
     * - el formulario contiene los campos necesarios
     * - los campos estan pre-rellenados con los datos del producto
     * - existe el campo _method con valor PUT para method spoofing
     * - la accion del formulario apunta a la ruta update
     * - existen selects para brand y category con la opcion actual seleccionada
     */
    public function test_route_edit_product_shows_form_with_data(): void
    {
        // arrange: crear datos necesarios
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $anotherBrand = Brand::factory()->create();

        $product = Product::factory()
            ->withBrandAndCategory($brand, $category)
            ->create();

        // act: solicitar formulario de edicion
        $response = $this->get(route('products.edit', ['product' => $product->id]));

        // assert: verificar estructura del formulario
        $response->assertStatus(200);
        $response->assertViewIs('product.form');
        $response->assertSeeHtml('<form');

        // verificar que los campos contienen los valores del producto
        $response->assertSee($product->product_name);
        $response->assertSee($product->product_desc);
        $response->assertSee($product->sku);
        $response->assertSee($product->barcode);
        $response->assertSee($product->price);
        $response->assertSee($product->cost);

        // verificar method spoofing para PUT
        $response->assertSee('_method', false);
        $response->assertSee('PUT', false);

        // verificar accion del formulario
        $response->assertSee(route('products.update', $product->id), false);

        // verificar que existen las opciones de brand y category
        $response->assertSee($brand->brand_name);
        $response->assertSee($category->category_name);
        $response->assertSee($anotherBrand->brand_name);
    }

    /**
     * verifica que el store valida campos obligatorios
     * - la peticion sin datos retorna codigo 302 (redirect back)
     * - existen errores de validacion en la sesion
     * - cada campo obligatorio tiene su error correspondiente
     * - no se persiste ningun registro en la base de datos
     */
    public function test_store_product_requires_mandatory_fields(): void
    {
        // arrange: datos vacios
        $emptyData = [];

        // act: intentar crear producto sin datos
        $response = $this->post(route('products.store'), $emptyData);

        // assert: verificar que falla la validacion
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'product_name',
            'sku',
            'price',
            'cost',
            'brand_id',
            'category_id'
        ]);

        // verificar que no se creo ningun producto
        $this->assertDatabaseCount('products', 0);
    }

    /**
     * verifica que el store valida campos con valores invalidos
     * - price y cost deben ser numericos positivos
     * - brand_id y category_id deben existir en sus respectivas tablas
     * - sku debe ser unico
     */
    public function test_store_product_validates_field_formats(): void
    {
        // arrange: crear producto existente para validar sku unico
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $existingProduct = Product::factory()
            ->withBrandAndCategory($brand, $category)
            ->create();

        // datos invalidos
        $invalidData = [
            'product_name' => 'producto test',
            'product_desc' => 'descripcion test',
            'sku' => $existingProduct->sku, // sku duplicado
            'barcode' => '123456789',
            'price' => -100, // precio negativo
            'cost' => 'invalid', // costo no numerico
            'brand_id' => 99999, // id inexistente
            'category_id' => 99999 // id inexistente
        ];

        // act: intentar crear producto con datos invalidos
        $response = $this->post(route('products.store'), $invalidData);

        // assert: verificar errores de validacion
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'sku',        // debe ser unico
            'price',      // debe ser positivo
            'cost',       // debe ser numerico
            'brand_id',   // debe existir
            'category_id' // debe existir
        ]);

        // verificar que no se creo el producto invalido
        $this->assertDatabaseCount('products', 1); // solo el existente
    }

    /**
     * verifica que el update valida campos obligatorios
     * - similar al store pero para actualizacion
     * - no debe permitir dejar campos obligatorios vacios
     */
    public function test_update_product_requires_mandatory_fields(): void
    {
        // arrange: crear producto existente
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();

        $product = Product::factory()
            ->withBrandAndCategory($brand, $category)
            ->create();

        // datos vacios
        $emptyData = [
            'product_name' => '',
            'sku' => '',
            'price' => '',
            'cost' => '',
            'brand_id' => '',
            'category_id' => ''
        ];

        // act: intentar actualizar con datos vacios
        $response = $this->put(
            route('products.update', ['product' => $product->id]),
            $emptyData
        );

        // assert: verificar que falla la validacion
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'product_name',
            'sku',
            'price',
            'cost',
            'brand_id',
            'category_id'
        ]);

        // verificar que el producto no se actualizo
        $product->refresh();
        $this->assertNotEmpty($product->product_name);
        $this->assertNotEmpty($product->sku);
    }
}
