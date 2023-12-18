<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Crea 10 usuarios utilizando el factory
        User::factory()->count(10)->create();
    }
}

