<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exercise>
 */
final class ExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $exercises = [
            'Bench Press',
            'Squat',
            'Deadlift',
            'Overhead Press',
            'Barbell Row',
            'Pull Ups',
            'Bicep Curls',
            'Tricep Extensions',
            'Lateral Raises',
            'Leg Press',
            'Lunges',
            'Romanian Deadlift',
            'Incline Bench Press',
            'Dumbbell Rows',
            'Face Pulls',
        ];

        return [
            'name' => fake()->randomElement($exercises),
        ];
    }
}
