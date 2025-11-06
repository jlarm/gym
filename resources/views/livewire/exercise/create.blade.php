<div>
    <flux:modal.trigger name="add-exercise">
        <flux:button variant="primary">Add Exercise</flux:button>
    </flux:modal.trigger>

    <flux:modal name="add-exercise" variant="flyout" class="w-full sm:w-96 md:w-[500px]">
        <form wire:submit="addExercise" class="space-y-6">
            <div>
                <flux:heading size="lg">Add Exercise</flux:heading>
            </div>

            <div>
                <flux:select
                    wire:model.live="selectedExerciseId"
                    variant="listbox"
                    searchable
                    placeholder="Search exercises..."
                    label="Exercise"
                >
                    @foreach($exercises as $exercise)
                        <flux:select.option value="{{ $exercise->id }}">
                            {{ $exercise->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:button
                    type="button"
                    wire:click="$set('showCreateModal', true)"
                    variant="filled"
                    icon="plus"
                    size="sm"
                    class="mt-2"
                    >
                    Create new exercise
                </flux:button>
            </div>

            {{-- Sets Section --}}
            @if($selectedExerciseId)
                {{-- Latest Sets Info --}}
                @if($this->latestSets->isNotEmpty())
                    <div class="rounded-lg bg-zinc-100 dark:bg-zinc-800 p-4">
                        <flux:heading size="sm" class="mb-2">Last time</flux:heading>
                        <div class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                            @foreach($this->latestSets as $set)
                                <div class="flex gap-2">
                                    <span class="font-medium">{{ $set->reps }} reps</span>
                                    @if($set->weight)
                                        <span>×</span>
                                        <span class="font-medium">{{ $set->weight }} lbs</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <flux:heading size="sm">Sets</flux:heading>
                        <flux:button
                            type="button"
                            wire:click="addSet"
                            variant="ghost"
                            size="sm"
                            icon="plus"
                        >
                            Add Set
                        </flux:button>
                    </div>

                    <div class="space-y-2">
                        @foreach($sets as $index => $set)
                            <div wire:key="set-{{ $index }}" class="flex gap-2 items-start">
                                <div class="flex-shrink-0 w-8 pt-2 text-sm text-zinc-500">
                                    {{ $index + 1 }}
                                </div>

                                <flux:input
                                    wire:model="sets.{{ $index }}.reps"
                                    type="number"
                                    placeholder="Reps"
                                    min="1"
                                    class="flex-1"
                                />

                                <flux:input
                                    wire:model="sets.{{ $index }}.weight"
                                    type="number"
                                    step="0.01"
                                    placeholder="Weight"
                                    min="0"
                                    class="flex-1"
                                />

                                <flux:button
                                    type="button"
                                    wire:click="removeSet({{ $index }})"
                                    variant="ghost"
                                    size="sm"
                                    icon="x-mark"
                                    class="flex-shrink-0"
                                />
                            </div>
                            @error("sets.{$index}.reps")
                                <flux:error>{{ $message }}</flux:error>
                            @enderror
                            @error("sets.{$index}.weight")
                                <flux:error>{{ $message }}</flux:error>
                            @enderror
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button
                    type="submit"
                    variant="primary"
                    :disabled="!$selectedExerciseId"
                >
                    Add Exercise
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="create-exercise" wire:model.self="showCreateModal">
        <form wire:submit="createNewExercise">
            <div class="space-y-6">
                <flux:heading size="lg">Create New Exercise</flux:heading>

                <flux:input
                    wire:key="new-exercise-input"
                    wire:model="newExerciseName"
                    label="Exercise Name"
                    placeholder="e.g., Bench Press"
                    autofocus
                />
                @error('newExerciseName')
                    <flux:error>{{ $message }}</flux:error>
                @enderror

                <div class="flex gap-2 justify-end">
                    <flux:button
                        type="button"
                        variant="ghost"
                        wire:click="$set('showCreateModal', false)"
                    >
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Create Exercise
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>
</div>
