<?php

declare(strict_types=1);

namespace App\Livewire\Workout;

use App\Models\Workout;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

final class WorkoutCharts extends Component
{
    #[Locked]
    public Workout $workout;

    /**
     * Get volume per exercise for the current workout
     *
     * @return array<int, array{exercise: string, volume: float}>
     */
    public function getExerciseVolumeData(): array
    {
        return $this->workout->exercises()
            ->with(['sets' => function ($query) {
                $query->where('workout_id', $this->workout->id);
            }])
            ->get()
            ->map(function ($exercise) {
                $totalVolume = $exercise->sets
                    ->where('workout_id', $this->workout->id)
                    ->sum(fn ($set) => $set->weight * $set->reps);

                return [
                    'exercise' => $exercise->name,
                    'volume' => (float) $totalVolume,
                ];
            })
            ->sortByDesc('volume')
            ->values()
            ->toArray();
    }

    /**
     * Get sets count per exercise
     *
     * @return array<int, array{exercise: string, sets: int}>
     */
    public function getExerciseSetsData(): array
    {
        return $this->workout->exercises()
            ->withCount(['sets' => function ($query) {
                $query->where('workout_id', $this->workout->id);
            }])
            ->get()
            ->map(fn ($exercise) => [
                'exercise' => $exercise->name,
                'sets' => $exercise->sets_count,
            ])
            ->sortByDesc('sets')
            ->values()
            ->toArray();
    }

    /**
     * Get summary statistics for the workout
     *
     * @return array{
     *     totalVolume: float,
     *     totalSets: int,
     *     avgWeight: float,
     *     maxWeight: float
     * }
     */
    public function getSummaryStats(): array
    {
        $sets = $this->workout->sets;

        return [
            'totalVolume' => (float) $sets->sum(fn ($set) => $set->weight * $set->reps),
            'totalSets' => $sets->count(),
            'avgWeight' => (float) $sets->avg('weight'),
            'maxWeight' => (float) $sets->max('weight'),
        ];
    }

    public function render(): View
    {
        return view('livewire.workout.workout-charts', [
            'exerciseVolumeData' => $this->getExerciseVolumeData(),
            'exerciseSetsData' => $this->getExerciseSetsData(),
            'stats' => $this->getSummaryStats(),
        ]);
    }
}
