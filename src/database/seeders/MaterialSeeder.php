<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            ['id' => 1, 'name' => '4mm Plywood'],
            ['id' => 2, 'name' => '6mm Plywood'],
            ['id' => 3, 'name' => '8mm Plywood'],
            ['id' => 4, 'name' => 'PLA'],
            ['id' => 5, 'name' => 'Arduino'],
        ];

        foreach ($materials as $material) {
            Material::forceCreate($material);
        }
    }
}
