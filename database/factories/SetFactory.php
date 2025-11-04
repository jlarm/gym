<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Set>
 */
final class SetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reps' => fake()->numberBetween(5, 15),
            'weight' => fake()->randomFloat(2, 45, 315),
            'order' => 1,
        ];
    }

    /**
     * Set for a lightweight exercise
     */
    public function light(): static
    {
        return $this->state(fn (array $attributes) => [
            'weight' => fake()->randomFloat(2, 10, 50),
            'reps' => fake()->numberBetween(8, 20),
        ]);
    }

    /**
     * Set for a heavy exercise
     */
    public function heavy(): static
    {
        return $this->state(fn (array $attributes) => [
            'weight' => fake()->randomFloat(2, 200, 400),
            'reps' => fake()->numberBetween(1, 6),
        ]);
    }
}
