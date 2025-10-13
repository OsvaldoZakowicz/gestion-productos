<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_name' => $this->faker->unique()->word(),
            'category_desc' => $this->faker->sentence(4),
        ];
    }

    /**
     * State personalizado. Sobreescribe definition().
     * Usaremos este state para crear categorias con nombre
     * y descripcion especificas a traves de factory()
     *
     * @param string $name
     * @return static
     */
    public function withNameAndDesc(string $name, string $desc): static
    {
        return $this->state(fn (array $attributes) => [
            'category_name' => $name,
            'category_desc' => $desc,
        ]);
    }
}
