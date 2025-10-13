<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // crear marcas usando el factory (fabrica) del modelo
        //Brand::factory()->count(5)->create();

        // crear marcas especificas
        $specific_brands = [
            'marolio', 'favorita', 'mantulak', 'presto pronta', 'natura', 'instituto',
            'coca cola', 'pepsi', 'terma', 'tramontina', 'tupperware',
        ];

        foreach ($specific_brands as $brand_name) {
            // llamo al state personalizado withName()
            Brand::factory()->withName($brand_name)->create();
        }

    }
}
