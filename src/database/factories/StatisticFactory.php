<?php

namespace Database\Factories;

use App\Models\Statistic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Statistic>
 */
class StatisticFactory extends Factory
{
    protected $model = Statistic::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ip' => fake()->ipv4(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'continent' => fake()->randomElement(['Europe', 'Asia', 'North America', 'South America', 'Africa', 'Oceania']),
            'page' => fake()->randomElement(['/', '/creations', '/about', '/contact']),
        ];
    }
}
