<?php

namespace Database\Factories;

use App\Enums\TalkStatus;
use App\Enums\TalkType;
use App\Models\Speaker;
use App\Models\TalkCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Talk>
 */
class TalkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'speaker_id' => Speaker::count() > 0 ? Speaker::query()->inRandomOrder()->first()->id : Speaker::factory(),
            'talk_category_id' => TalkCategory::count() > 0 ? TalkCategory::query()->inRandomOrder()->first()->id : TalkCategory::factory(),
            'title' => $this->faker->sentence,
            'abstract' => $this->faker->paragraph,
            'description' => $this->faker->text,
            'type' => $this->faker->randomElement(TalkType::cases())->value,
            'duration' => $this->faker->randomElement([30, 45, 60]),
            'status' => $this->faker->randomElement(TalkStatus::cases())->value,
        ];
    }
}
