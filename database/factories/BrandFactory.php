<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    // Modelo asociado a este factory
    protected $model = Brand::class;

    /**
     * Define como crear una instancia del modelo.
     * cuando usamos el factory, este determina el valor de los
     * atributos que se usaran en los modelos a crear.
     * Aqui podemos usar FakerPHP.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand_name' => $this->faker->unique()->company(),
        ];
    }

    /**
     * State personalizado. Sobreescribe definition().
     * Usaremos este state para crear marcas con un nombre especifico
     * a traves del factory()
     *
     * @param string $name
     * @return static
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'brand_name' => $name,
        ]);
    }
}
