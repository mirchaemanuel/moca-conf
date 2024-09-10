<?php

namespace Database\Factories;

use App\Models\Speaker;
use App\Models\Talk;
use Database\Seeders\AvatarImage;
use File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends Factory<Speaker>
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
            'last_name'  => $this->faker->lastName,
            'country'    => $this->faker->countryCode,

            'nickname' => $this->faker->userName,

            'avatar' => $this->faker->imageUrl(),
            'bio'    => $this->faker->paragraph,

            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,

            'company'   => $this->faker->company,
            'job_title' => $this->faker->jobTitle,

            'linkedin'  => $this->faker->url,
            'twitter'   => $this->faker->url,
            'facebook'  => $this->faker->url,
            'instagram' => $this->faker->url,

            'notes' => $this->faker->paragraph,
        ];
    }

    public function configure(): SpeakerFactory
    {
        return $this->afterCreating(function (Speaker $speaker) {
            $avatar_file = AvatarImage::getRandomFile();
            $tempName = Str::random(40) . '.' . $avatar_file->getExtension();

            Storage::disk('public')->putFileAs('avatars', $avatar_file->getRealPath(), $tempName);

            $speaker->update([
                'avatar' => 'avatars/' . $tempName,
            ]);
        });
    }

    public function withTalks(int $count = 1): static
    {
        return $this->has(
            Talk::factory()->state(
                fn(array $attributes, Speaker $speaker) => ['speaker_id' => $speaker->id]
            )->count($count),
            'talks'
        );
    }
}
