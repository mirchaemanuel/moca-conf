<?php

namespace Database\Seeders;

use App;
use App\Models\Conference;
use Illuminate\Database\Seeder;

class AddLocalConferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::environment('local')) {
            Conference::factory()->count(5)->create();
        }
    }
}
