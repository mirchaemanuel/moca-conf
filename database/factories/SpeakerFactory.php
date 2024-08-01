<?php

namespace Database\Factories;

use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Speaker>
 */
class SpeakerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'nickname' => $this->faker->userName,

            'avatar' => $this->faker->imageUrl(),
            'bio' => $this->faker->paragraph,

            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,

            'company' => $this->faker->company,
            'job_title' => $this->faker->jobTitle,

            'linkedin' => $this->faker->url,
            'twitter' => $this->faker->url,
            'facebook' => $this->faker->url,
            'instagram' => $this->faker->url,

            'notes' => $this->faker->paragraph,
        ];
    }

    public function withTalks(int $count = 1): static
    {
        return $this->has(
            Talk::factory()->state(
                fn (array $attributes, Speaker $speaker) => ['speaker_id' => $speaker->id]
            )->count($count),
            'talks'
        );
    }
}
