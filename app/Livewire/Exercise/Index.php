<?php

declare(strict_types=1);

namespace App\Livewire\Exercise;

use App\Models\Workout;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

final class Index extends Component
{
    #[Locked]
    public Workout $workout;

    #[On('exercise-added')]
    public function refreshExercises(): void
    {
        // This will trigger a re-render to show the new exercise
    }

    public function removeExercise(int $exerciseId): void
    {
        $this->workout->exercises()->detach($exerciseId);
        $this->workout->sets()->where('exercise_id', $exerciseId)->delete();
    }

    public function removeSet(int $setId): void
    {
        $this->workout->sets()->where('id', $setId)->delete();
    }

    public function render(): View
    {
        return view('livewire.exercise.index', [
            'exercises' => $this->workout->exercises()->with(['sets' => function ($query) {
                $query->where('workout_id', $this->workout->id)->orderBy('order');
            }])->orderBy('exercise_workout.order')->get(),
        ]);
    }
}
