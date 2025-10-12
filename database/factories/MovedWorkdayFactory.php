<?php

declare(strict_types=1);

namespace AAEngineering\WorkdayManager\Database\Factories;

use AAEngineering\WorkdayManager\Models\MovedWorkday;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MovedWorkday>
 */
final class MovedWorkdayFactory extends Factory
{
    protected $model = MovedWorkday::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'day' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'type' => $this->faker->randomElement(['holiday', 'workday']),
        ];
    }

    /**
     * Indicate that the moved workday is a weekend/holiday converted to workday.
     */
    public function workday(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'workday',
        ]);
    }

    /**
     * Indicate that the moved workday is a workday converted to holiday.
     */
    public function holiday(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => 'holiday',
        ]);
    }
}
