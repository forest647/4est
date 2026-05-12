<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Forest',
            'email' => 'admin@4est.info',
            'password' => bcrypt('CHANGE_ME_IMMEDIATELY'),
            'role' => 'admin',
        ]);
    }
}
