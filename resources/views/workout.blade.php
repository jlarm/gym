<x-layouts.app :title="__('Workout')">
    <div class="max-w-4xl mx-auto px-4 py-6">
        <flux:heading class="mb-6" size="lg">{{ $workout->created_at->format('F d, Y') }}</flux:heading>

        <div class="space-y-6">
            <livewire:exercise.create :$workout />
            <livewire:exercise.index :$workout />
        </div>
    </div>
</x-layouts.app>
