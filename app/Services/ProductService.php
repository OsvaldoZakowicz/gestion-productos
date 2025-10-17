<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

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
     */
    public function getProduct(int $id): Product
    {
        return Product::findOrFail($id);
    }

    /**
     * Crear un producto
     * @param array $data datos validados
     * @return App\Models\Product
     */
    public function createProduct(array $data): Product
    {
        return Product::create([
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
    }

    /**
     * Actualizar un producto
     * @param array $data datos validados
     * @param int $product_id id de producto
     * @return bool
     */
    public function updateProduct(array $data, int $product_id): bool
    {
        return Product::where('id', $product_id)->update($data);
    }

    /**
     * Eliminar producto
     * @param int $product_id id de producto
     * @return bool
     */
    public function deleteProduct(int $product_id): bool
    {
        return Product::where('id', $product_id)->delete();
    }
}