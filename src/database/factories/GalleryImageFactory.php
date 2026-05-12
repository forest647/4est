<?php

namespace Database\Factories;

use App\Models\Creation;
use App\Models\GalleryImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GalleryImage>
 */
class GalleryImageFactory extends Factory
{
    protected $model = GalleryImage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'creation_id' => Creation::factory(),
            'filename' => fake()->numerify('IMG_####.jpg'),
            'ranking' => fake()->numberBetween(0, 10),
        ];
    }
}
