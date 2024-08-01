<?php

namespace Database\Factories;

use App\Enums\ConferenceStatus;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conference>
 */
class ConferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $startDate = $this->faker->dateTimeBetween('now', '+1 year');
        $endDate = Carbon::parse($startDate)->addDays($this->faker->numberBetween(3, 5));

        return [
            'venue_id' => Venue::count() ? Venue::inRandomOrder()->first()->id : Venue::factory(),
            'name' => $this->faker->domainName,
            'slug' => $this->faker->slug,

            'description' => $this->faker->text,

            'start_date' => $startDate,
            'end_date' => $endDate,

            'status' => $this->faker->randomElement(ConferenceStatus::cases())->value,

        ];
    }
}
