<?php

namespace Database\Seeders;

use App\Models\Conference;
use App\Models\ConferenceTalk;
use App\Models\Talk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class AttachTalkConferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::environment('local') && ConferenceTalk::count() === 0) {

            $talk_ids = Talk::pluck('id');

            Conference::all()->each(function (Conference $conference) use ($talk_ids) {
                $selected_talk_ids = collect($talk_ids)->shuffle()->take(3)->all();

                $conference->talks()->attach([
                    $selected_talk_ids[0] => ['date_time' => $conference->start_date->addDays(1)],
                    $selected_talk_ids[1] => ['date_time' => $conference->start_date->addDays(2)],
                    $selected_talk_ids[2] => ['date_time' => $conference->start_date->addDays(3)]
                ]);
            });
        }
    }
}
