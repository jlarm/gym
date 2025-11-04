<x-layouts.app :title="__('Workout')">
    <div class="max-w-4xl mx-auto flex flex-col items-center">
        <flux:heading class="mb-6" size="lg">{{ $workout->created_at->format('F d, Y') }}</flux:heading>
        <livewire:exercise.create :$workout />
    </div>
</x-layouts.app>
