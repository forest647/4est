<?php

namespace Database\Factories;

use App\Models\Creation;
use App\Models\CreationTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreationTranslation>
 */
class CreationTranslationFactory extends Factory
{
    protected $model = CreationTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'creation_id' => Creation::factory(),
            'locale' => 'en',
            'name' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(3),
            'description' => fake()->paragraphs(3, true),
        ];
    }
}
