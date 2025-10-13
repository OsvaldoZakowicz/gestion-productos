<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // crear categorias usando la fabrica del modelo
        //Category::factory()->count(5)->create();

        $specific_categories = [
            'pastas' => 'fideos de todo tipo',
            'harinas' => 'harina integral, harina 000, harina 0000',
            'bebidas' => 'gaseosas o bebidas con alcohol',
            'bazar' => 'platos, cubiertos, tuppers',
            'otros' => 'otros productos',
        ];

        foreach ($specific_categories as $name => $desc) {
            Category::factory()->withNameAndDesc($name, $desc)->create();
        }
    }
}
