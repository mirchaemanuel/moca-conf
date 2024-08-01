<?php

namespace Database\Seeders;

use App;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class AddLocalVenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::environment('local')) {
            Venue::factory()->count(5)->create();
        }
    }
}
