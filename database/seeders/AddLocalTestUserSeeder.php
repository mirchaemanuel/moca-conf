<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class AddLocalTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::environment() === 'local') {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'm@a80.it',
                'password' => bcrypt('password'),
            ]);
        }
    }
}
