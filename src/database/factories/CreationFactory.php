<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Creation;
use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Creation>
 */
class CreationFactory extends Factory
{
    protected $model = Creation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'size' => fake()->numerify('### x ### x ### mm'),
            'price' => fake()->randomFloat(2, 0, 500),
            'download' => null,
            'category_id' => Category::factory(),
            'material_id' => Material::factory(),
        ];
    }
}
