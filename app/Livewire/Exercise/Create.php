<?php

declare(strict_types=1);

namespace App\Livewire\Exercise;

use App\Models\Exercise;
use App\Models\Set;
use App\Models\Workout;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

final class Create extends Component
{
    #[Locked]
    public Workout $workout;

    public ?int $selectedExerciseId = null;

    public string $newExerciseName = '';

    public bool $showCreateModal = false;

    public array $sets = [];

    public function mount(): void
    {
        $this->addSet();
    }

    /**
     * Get the latest sets for the selected exercise
     *
     * @return Collection<int, Set>
     */
    #[Computed]
    public function latestSets(): Collection
    {
        if (! $this->selectedExerciseId) {
            return collect();
        }

        return Set::query()
            ->where('exercise_id', $this->selectedExerciseId)
            ->whereHas('workout', fn ($query) => $query->where('id', '!=', $this->workout->id))
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }

    public function addSet(): void
    {
        $this->sets[] = [
            'reps' => '',
            'weight' => '',
        ];
    }

    public function removeSet(int $index): void
    {
        unset($this->sets[$index]);
        $this->sets = array_values($this->sets);

        if (count($this->sets) === 0) {
            $this->addSet();
        }
    }

    public function addExercise(): void
    {
        $this->validate([
            'selectedExerciseId' => ['required', 'exists:exercises,id'],
            'sets.*.reps' => ['required', 'integer', 'min:1'],
            'sets.*.weight' => ['nullable', 'integer', 'min:0'],
        ]);

        $this->workout->exercises()->attach($this->selectedExerciseId, [
            'order' => $this->workout->exercises()->count() + 1,
        ]);

        foreach ($this->sets as $index => $setData) {
            Set::create([
                'workout_id' => $this->workout->id,
                'exercise_id' => $this->selectedExerciseId,
                'reps' => $setData['reps'],
                'weight' => $setData['weight'],
                'order' => $index + 1,
            ]);
        }

        $this->selectedExerciseId = null;
        $this->sets = [];
        $this->addSet();

        $this->dispatch('exercise-added');
        $this->modal('add-exercise')->close();
    }

    public function createNewExercise(): void
    {
        $this->validate([
            'newExerciseName' => ['required', 'string', 'max:255', 'unique:exercises,name'],
        ]);

        $exercise = Exercise::create([
            'name' => $this->newExerciseName,
        ]);

        $this->selectedExerciseId = $exercise->id;
        $this->newExerciseName = '';
        $this->showCreateModal = false;

        $this->dispatch('exercise-created');
    }

    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
    }

    public function render(): View
    {
        return view('livewire.exercise.create', [
            'exercises' => Exercise::orderBy('name')->get(),
        ]);
    }
}
