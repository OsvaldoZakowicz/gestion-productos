<?php

namespace App\Services;

use App\Exceptions\Database\DatabaseTransactionException;
use App\Exceptions\Product\ProductNotFoundException;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * Obtener todos los productos, paginados
     * @return Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllProducts(): LengthAwarePaginator
    {
        $query = Product::latest();

        return $query->paginate(Product::PAGINATE);
    }

    /**
     * Obtener las marcas disponibles para productos
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBrandsForProduct(): Collection
    {
        return Brand::all();
    }

    /**
     * Obtener las categorias disponibles para productos
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategoriesForProduct(): Collection
    {
        return Category::all();
    }

    /**
     * Obtener un producto
     * @param int $id de producto
     * @return App\Models\Product
     * @throws ProductNotFoundException
     */
    public function getProduct(int $id): Product
    {
        try {
            return Product::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ProductNotFoundException();
        }
    }

    /**
     * Crear un producto
     * @param array $data datos validados
     * @return App\Models\Product
     * @throws DatabaseTransactionException
     */
    public function createProduct(array $data): Product
    {
        try {
            DB::beginTransaction();

            $new_product = Product::create([
                'product_name'  => $data['product_name'],
                'product_desc'  => $data['product_desc'],
                'sku'           => $data['sku'],
                'barcode'       => $data['barcode'],
                'price'         => (float) $data['price'],
                'cost'          => (float) $data['cost'],
                'tax_rate'      => Product::TAX,
                'is_active'     => true,
                'brand_id'      => $data['brand_id'],
                'category_id'   => $data['category_id']
            ]);

            DB::commit();
            return $new_product;
        } catch (\Throwable $th) {

            DB::rollBack();
            throw new DatabaseTransactionException(
                'no fue posible crear el producto, intente nuevamente.',
                'products.index'
            );
        }
    }

    /**
     * Actualizar un producto
     * @param array $data datos validados
     * @param int $product_id id de producto
     * @return bool
     * @throws DatabaseTransactionException
     */
    public function updateProduct(array $data, int $product_id): bool
    {
        try {

            DB::beginTransaction();
            $was_updated = Product::where('id', $product_id)->update($data);
            DB::commit();
            return $was_updated;
        } catch (\Throwable $th) {
           
            DB::rollBack();
            throw new DatabaseTransactionException(
                'no fue posible actualizar el producto, intente nuevamente.',
                'products.index'
            );
        }
    }

    /**
     * Eliminar producto
     * todo: restricciones para borrar un producto?
     *  - existencia de stock?
     *  - soft deletes?
     *  - desactivacion?
     * @param int $product_id id de producto
     * @return bool
     */
    public function deleteProduct(int $product_id): bool
    {
        return Product::where('id', $product_id)->delete();
    }
}