<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimeEntry>
 */
class TimeEntryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'recorded_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Configure the model to have a specific recorded date.
     */
    public function onDate(string $date): static
    {
        return $this->state(fn (array $attributes): array => [
            'recorded_at' => fake()->dateTimeBetween("$date 00:00:00", "$date 23:59:59"),
        ]);
    }

    /**
     * Configure the model to have a recorded time in the morning (8:00-12:00).
     */
    public function morning(): static
    {
        return $this->state(function (array $attributes): array {
            $date   = fake()->dateTimeBetween('-30 days', 'now');
            $hour   = fake()->numberBetween(8, 11);
            $minute = fake()->numberBetween(0, 59);

            $dateTime = (clone $date)->setTime($hour, $minute);

            return [
                'recorded_at' => $dateTime,
            ];
        });
    }

    /**
     * Configure the model to have a recorded time in the afternoon (13:00-18:00).
     */
    public function afternoon(): static
    {
        return $this->state(function (array $attributes): array {
            $date   = fake()->dateTimeBetween('-30 days', 'now');
            $hour   = fake()->numberBetween(13, 17);
            $minute = fake()->numberBetween(0, 59);

            $dateTime = (clone $date)->setTime($hour, $minute);

            return [
                'recorded_at' => $dateTime,
            ];
        });
    }
}
