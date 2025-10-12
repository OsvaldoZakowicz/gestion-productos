<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
     */
    public function createBrand(array $data): Brand
    {
        $new_brand =  Brand::create([
            'brand_name' => $data['brand_name'],
        ]);

        return $new_brand;
    }

    /**
     * obtener una marca
     * @param int $brand_id brand
     * @return \App\Models\Brand
     */
    public function getBrand(int $brand_id): Brand
    {
        return Brand::findOrFail($brand_id);
    }

    /**
     * actualizar una marca
     * @param int $brand_id brand
     * @param array $data parametros de la nueva marca validados
     * @return bool
     */
    public function updateBrand(int $brand_id, array $data): bool
    {
        // NOTA: el array data debe contener pares clave => valor coincidentes con los atributos de el modelo Brand
        return Brand::where('id', $brand_id)->update($data);
    }

    /**
     * eliminar una marca
     * @param int $brand_id brand
     * @return bool
     */
    public function deleteBrand(int $brand_id): bool
    {
        return Brand::where('id', $brand_id)->delete();
    }
}