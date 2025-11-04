<?php

declare(strict_types=1);

namespace App\Livewire\Workout;

use App\Models\Set;
use App\Models\Workout;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

final class Edit extends Component
{
    #[Locked]
    public Workout $workout;

    public string $workoutDate = '';

    /** @var array<int, array{exercise_id: int, exercise_name: string, sets: array<int, array{id: int, reps: int, weight: float|null}>}> */
    public array $exercises = [];

    public function mount(): void
    {
        $this->workoutDate = $this->workout->created_at->format('Y-m-d');

        $this->exercises = $this->workout->exercises()
            ->with(['sets' => function ($query) {
                $query->where('workout_id', $this->workout->id)->orderBy('order');
            }])
            ->orderBy('exercise_workout.order')
            ->get()
            ->map(function ($exercise) {
                return [
                    'exercise_id' => $exercise->id,
                    'exercise_name' => $exercise->name,
                    'sets' => $exercise->sets->map(function ($set) {
                        return [
                            'id' => $set->id,
                            'reps' => $set->reps,
                            'weight' => $set->weight,
                        ];
                    })->toArray(),
                ];
            })
            ->toArray();
    }

    public function save(): void
    {
        $this->validate([
            'workoutDate' => ['required', 'date'],
            'exercises.*.sets.*.reps' => ['required', 'integer', 'min:1'],
            'exercises.*.sets.*.weight' => ['nullable', 'numeric', 'min:0'],
        ]);

        $this->workout->update([
            'created_at' => Carbon::parse($this->workoutDate),
        ]);

        foreach ($this->exercises as $exercise) {
            foreach ($exercise['sets'] as $set) {
                Set::where('id', $set['id'])->update([
                    'reps' => $set['reps'],
                    'weight' => $set['weight'],
                ]);
            }
        }

        $this->dispatch('workout-updated');
        $this->redirect(route('workout', $this->workout), navigate: true);
    }

    public function cancel(): void
    {
        $this->redirect(route('workout', $this->workout), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.workout.edit')
            ->layout('components.layouts.app', ['title' => __('Edit Workout')]);
    }
}
