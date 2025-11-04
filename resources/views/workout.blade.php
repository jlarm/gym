<x-layouts.app :title="__('Workout')">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="mb-6 flex items-center justify-between">
            <flux:heading size="lg">{{ $workout->created_at->format('F d, Y') }}</flux:heading>
            <flux:button
                href="{{ route('workout.edit', $workout) }}"
                wire:navigate
                variant="ghost"
                size="sm"
                icon="pencil"
            >
                Edit Workout
            </flux:button>
        </div>

        <div class="space-y-8">
            <livewire:workout.workout-charts :$workout />

            <livewire:exercise.create :$workout />
            <livewire:exercise.index :$workout />
        </div>
    </div>
</x-layouts.app>
