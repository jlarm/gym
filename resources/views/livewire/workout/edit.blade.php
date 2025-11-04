<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <flux:heading size="lg">Edit Workout</flux:heading>
        <flux:button wire:click="cancel" variant="ghost" size="sm">
            Cancel
        </flux:button>
    </div>

    <form wire:submit="save" class="space-y-6">
        {{-- Workout Date --}}
        <div>
            <flux:field>
                <flux:label>Workout Date</flux:label>
                <flux:date-picker
                    type="date"
                    wire:model="workoutDate"
                    required
                />
                <flux:error name="workoutDate" />
            </flux:field>
        </div>

        {{-- Exercises and Sets --}}
        <div class="space-y-4">
            @forelse($exercises as $exerciseIndex => $exercise)
                <div wire:key="exercise-{{ $exercise['exercise_id'] }}" class="border dark:border-zinc-700 rounded-lg overflow-hidden">
                    {{-- Exercise Header --}}
                    <div class="bg-zinc-50 dark:bg-zinc-800 px-4 py-3">
                        <flux:heading size="sm">{{ $exercise['exercise_name'] }}</flux:heading>
                    </div>

                    {{-- Sets List --}}
                    @if(count($exercise['sets']) > 0)
                        <div class="divide-y dark:divide-zinc-700">
                            @foreach($exercise['sets'] as $setIndex => $set)
                                <div wire:key="set-{{ $set['id'] }}" class="px-4 py-3">
                                    <div class="flex items-center gap-4">
                                        <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400 w-8">
                                            {{ $setIndex + 1 }}
                                        </div>
                                        <div class="flex gap-4 flex-1">
                                            <flux:field class="flex-1">
                                                <flux:label>Reps</flux:label>
                                                <flux:input
                                                    type="number"
                                                    wire:model="exercises.{{ $exerciseIndex }}.sets.{{ $setIndex }}.reps"
                                                    min="1"
                                                    required
                                                />
                                                <flux:error name="exercises.{{ $exerciseIndex }}.sets.{{ $setIndex }}.reps" />
                                            </flux:field>
                                            <flux:field class="flex-1">
                                                <flux:label>Weight (lbs)</flux:label>
                                                <flux:input
                                                    type="number"
                                                    wire:model="exercises.{{ $exerciseIndex }}.sets.{{ $setIndex }}.weight"
                                                    step="0.01"
                                                    min="0"
                                                />
                                                <flux:error name="exercises.{{ $exerciseIndex }}.sets.{{ $setIndex }}.weight" />
                                            </flux:field>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400 text-center">
                            No sets recorded
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-8 text-zinc-500 dark:text-zinc-400">
                    <flux:icon.clipboard-document-list class="size-12 mx-auto mb-2 opacity-50" />
                    <p>No exercises to edit</p>
                </div>
            @endforelse
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-4">
            <flux:button type="button" wire:click="cancel" variant="ghost">
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">
                <span wire:loading.remove wire:target="save">Save Changes</span>
                <span wire:loading wire:target="save">Saving...</span>
            </flux:button>
        </div>
    </form>
</div>
