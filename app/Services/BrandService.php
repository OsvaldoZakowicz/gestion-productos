<?php

namespace App\Services;

use App\Exceptions\Brand\BrandAlreadyInUseException;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Brand\BrandNotFoundException;
use App\Exceptions\Database\DatabaseTransactionException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BrandService
{

    /**
     * obtener un listado de marcas
     * @return LengthAwarePaginator
     */
    public function getAllBrands(): LengthAwarePaginator
    {
        $query = Brand::latest();

        return $query->paginate(Brand::PAGINATE);
    }

    /**
     * crear una nueva marca de producto
     * @param array $data parametros de la nueva marca validados
     * @return \App\Models\Brand
     * @throws DatabaseTransactionException
     */
    public function createBrand(array $data): Brand
    {
        try {

            DB::beginTransaction();
            $new_brand = Brand::create(['brand_name' => $data['brand_name']]);
            DB::commit();
            return $new_brand;
        } catch (\Throwable $th) {

            DB::rollBack();
            throw new DatabaseTransactionException(
                'no fue posible crear la marca, intente nuevamente.',
                'brands.index'
            );
        }
    }

    /**
     * obtener una marca
     * @param int $brand_id brand
     * @return \App\Models\Brand
     * @throws ModelNotFoundException
     */
    public function getBrand(int $brand_id): Brand
    {
        try {
            return Brand::findOrFail($brand_id);
        } catch (ModelNotFoundException $e) {
            throw new BrandNotFoundException();
        }
    }

    /**
     * actualizar una marca
     * @param int $brand_id brand
     * @param array $data parametros de la nueva marca validados
     * @return bool
     * @throws DatabaseTransactionException
     */
    public function updateBrand(int $brand_id, array $data): bool
    {
        try {
            DB::beginTransaction();
            // NOTA: el array data debe contener pares clave => valor
            // coincidentes con los atributos de el modelo Brand
            $was_updated = Brand::where('id', $brand_id)->update($data);
            DB::commit();

            return $was_updated;
        } catch (\Throwable $th) {

            DB::rollBack();
            throw new DatabaseTransactionException(
                'no fue posible actualizar la marca, intente nuevamente.',
                'brands.index'
            );
        }

    }

    /**
     * eliminar una marca
     * @param int $brand_id brand
     * @return bool
     * @throws DatabaseTransactionException
     * @throws BrandAlreadyInUseException
     */
    public function deleteBrand(int $brand_id): bool
    {
        if ($this->isBrandAlreadyInUse($brand_id)) {
            throw new BrandAlreadyInUseException();
        }

        try {
            DB::beginTransaction();
            $was_deleted = Brand::where('id', $brand_id)->delete();
            DB::commit();

            return $was_deleted;
        } catch (\Throwable $th) {
            throw new DatabaseTransactionException(
                'no fue posible borrar la marca, intente nuevamente',
                'brands.index'
            );
        }
    }

    /**
     * comprobar si una marca esta siendo usada por productos
     * @param int $brand_id id de marca
     * @return bool
     */
    private function isBrandAlreadyInUse(int $brand_id): bool
    {
        $brand = $this->getBrand($brand_id);

        $related_products_count = $brand->products->count();

        if ($related_products_count > 0) {
            return true;
        }

        return false;
    }
}
