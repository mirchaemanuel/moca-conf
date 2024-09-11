<?php

namespace Database\Seeders;

use App\Models\Speaker;
use App\Models\TalkCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class AddLocalSpeakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::environment() === 'local' && Speaker::count() === 0) {

            // Clear avatar images
            Storage::deleteDirectory('public/avatars');

            // create a bunch of category
            TalkCategory::factory()->count(10)->create();

            // then create a bunch of speakers with talk
            Speaker::factory()->count(20)->withTalks(
                rand(1, 2)
            )->create();

            // then create a bunch of speakers without talk
            Speaker::factory()->count(10)->create();
        }
    }


}
