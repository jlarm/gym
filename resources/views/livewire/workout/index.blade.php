<div class="space-y-4">
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="lg">Recent Workouts</flux:heading>
        <flux:button
            href="{{ route('workout.create') }}"
            variant="primary"
            icon="plus"
        >
            New Workout
        </flux:button>
    </div>

    @forelse($workouts as $workout)
        <a
            href="{{ route('workout', $workout->uuid) }}"
            wire:key="workout-{{ $workout->id }}"
            class="block border dark:border-zinc-700 rounded-lg hover:border-zinc-400 dark:hover:border-zinc-500 transition-colors"
        >
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <flux:heading size="sm">
                        {{ $workout->created_at->format('l, F d, Y') }}
                    </flux:heading>
                    <flux:button
                        type="button"
                        wire:click.prevent="deleteWorkout({{ $workout->id }})"
                        wire:confirm="Are you sure you want to delete this workout? This will remove all exercises and sets."
                        variant="ghost"
                        size="sm"
                        icon="trash"
                    />
                </div>

                <div class="flex gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                    <div class="flex items-center gap-1">
                        <flux:icon.clipboard-document-list class="size-4" />
                        <span>{{ $workout->exercises_count }} {{ Str::plural('exercise', $workout->exercises_count) }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <flux:icon.chart-bar class="size-4" />
                        <span>{{ $workout->sets_count }} {{ Str::plural('set', $workout->sets_count) }}</span>
                    </div>
                </div>

                @if($workout->created_at->isToday())
                    <flux:badge size="sm" class="mt-2" color="green">Today</flux:badge>
                @elseif($workout->created_at->isYesterday())
                    <flux:badge size="sm" class="mt-2" color="zinc">Yesterday</flux:badge>
                @endif
            </div>
        </a>
    @empty
        <div class="text-center py-12 border dark:border-zinc-700 rounded-lg">
            <flux:icon.clipboard-document-list class="size-16 mx-auto mb-4 text-zinc-400 dark:text-zinc-600" />
            <flux:heading size="lg" class="mb-2">No workouts yet</flux:heading>
            <flux:text class="mb-4">Start tracking your fitness journey today</flux:text>
            <flux:button
                href="{{ route('workout.create') }}"
                variant="primary"
                icon="plus"
            >
                Create Your First Workout
            </flux:button>
        </div>
    @endforelse
</div>
