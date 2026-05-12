<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'name' => 'Laser', 'slug' => 'laser'],
            ['id' => 2, 'name' => '3D Print', 'slug' => '3d-print'],
            ['id' => 3, 'name' => 'Electronics', 'slug' => 'electronics'],
            ['id' => 4, 'name' => 'Plans', 'slug' => 'plans'],
        ];

        foreach ($categories as $category) {
            Category::forceCreate($category);
        }
    }
}
