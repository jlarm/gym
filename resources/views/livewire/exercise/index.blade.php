<div class="space-y-4">
    @forelse($exercises as $exercise)
        <div wire:key="exercise-{{ $exercise->id }}" class="border dark:border-zinc-700 rounded-lg overflow-hidden">
            {{-- Exercise Header --}}
            <div class="bg-zinc-50 dark:bg-zinc-800 px-4 py-3 flex items-center justify-between">
                <flux:heading size="sm">{{ $exercise->name }}</flux:heading>
                <flux:button
                    type="button"
                    wire:click="removeExercise({{ $exercise->id }})"
                    wire:confirm="Are you sure you want to remove this exercise and all its sets?"
                    variant="ghost"
                    size="sm"
                    icon="trash"
                />
            </div>

            {{-- Sets List --}}
            @if($exercise->sets->isNotEmpty())
                <div class="divide-y dark:divide-zinc-700">
                    @foreach($exercise->sets as $set)
                        <div wire:key="set-{{ $set->id }}" class="px-4 py-3 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400 w-8">
                                    {{ $set->order }}
                                </div>
                                <div class="flex gap-4 text-sm">
                                    <div>
                                        <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $set->reps }}</span>
                                        <span class="text-zinc-500 dark:text-zinc-400">reps</span>
                                    </div>
                                    @if($set->weight)
                                        <div>
                                            <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $set->weight }}</span>
                                            <span class="text-zinc-500 dark:text-zinc-400">lbs</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <flux:button
                                type="button"
                                wire:click="removeSet({{ $set->id }})"
                                wire:confirm="Are you sure you want to remove this set?"
                                variant="ghost"
                                size="sm"
                                icon="x-mark"
                            />
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
            <p>No exercises added yet</p>
            <p class="text-sm">Click "Add Exercise" to get started</p>
        </div>
    @endforelse
</div>
