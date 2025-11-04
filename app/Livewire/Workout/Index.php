<?php

declare(strict_types=1);

namespace App\Livewire\Workout;

use App\Models\Workout;
use Illuminate\View\View;
use Livewire\Component;

final class Index extends Component
{
    public function deleteWorkout(int $workoutId): void
    {
        $workout = Workout::findOrFail($workoutId);

        $workout->sets()->delete();
        $workout->exercises()->detach();
        $workout->delete();
    }

    public function render(): View
    {
        return view('livewire.workout.index', [
            'workouts' => Workout::withCount(['exercises', 'sets'])
                ->latest()
                ->get(),
        ]);
    }
}
