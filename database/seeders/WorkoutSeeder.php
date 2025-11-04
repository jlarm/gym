<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Set;
use App\Models\Workout;
use Illuminate\Database\Seeder;

final class WorkoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exercises = [
            ['name' => 'Bench Press', 'type' => 'heavy'],
            ['name' => 'Squat', 'type' => 'heavy'],
            ['name' => 'Deadlift', 'type' => 'heavy'],
            ['name' => 'Overhead Press', 'type' => 'medium'],
            ['name' => 'Barbell Row', 'type' => 'medium'],
            ['name' => 'Pull Ups', 'type' => 'medium'],
            ['name' => 'Bicep Curls', 'type' => 'light'],
            ['name' => 'Tricep Extensions', 'type' => 'light'],
            ['name' => 'Lateral Raises', 'type' => 'light'],
        ];

        $createdExercises = collect($exercises)->map(function ($exercise) {
            return Exercise::firstOrCreate(['name' => $exercise['name']], [
                'name' => $exercise['name'],
            ]);
        });

        for ($i = 30; $i >= 0; $i--) {
            if (rand(1, 10) > 3) {
                $date = now()->subDays($i);
                $workout = Workout::factory()->forDate($date->format('Y-m-d H:i:s'))->create();

                $exerciseCount = rand(3, 6);
                $selectedExercises = $createdExercises->random($exerciseCount);

                foreach ($selectedExercises as $index => $exercise) {
                    $workout->exercises()->attach($exercise->id, [
                        'order' => $index + 1,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    $exerciseType = collect($exercises)->firstWhere('name', $exercise->name)['type'];

                    $setCount = rand(3, 5);
                    for ($setNum = 1; $setNum <= $setCount; $setNum++) {
                        $weight = match ($exerciseType) {
                            'heavy' => rand(135, 315),
                            'medium' => rand(65, 135),
                            'light' => rand(15, 50),
                        };

                        $reps = match ($exerciseType) {
                            'heavy' => rand(3, 8),
                            'medium' => rand(6, 12),
                            'light' => rand(10, 20),
                        };

                        Set::factory()->create([
                            'workout_id' => $workout->id,
                            'exercise_id' => $exercise->id,
                            'weight' => $weight,
                            'reps' => $reps,
                            'order' => $setNum,
                            'created_at' => $date,
                            'updated_at' => $date,
                        ]);
                    }
                }
            }
        }
    }
}
