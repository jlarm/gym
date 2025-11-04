<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Set;
use App\Models\Workout;
use Illuminate\View\View;
use Livewire\Component;

final class WorkoutStats extends Component
{
    public int $daysToShow = 30;

    /**
     * Get total volume (weight * reps) per day for the chart
     *
     * @return array<int, array{date: string, volume: float}>
     */
    public function getVolumeData(): array
    {
        $startDate = now()->subDays($this->daysToShow)->startOfDay();

        return Set::query()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(weight * reps) as volume')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($item) => [
                'date' => $item->date,
                'volume' => (float) $item->volume,
            ])
            ->toArray();
    }

    /**
     * Get total sets per day for the chart
     *
     * @return array<int, array{date: string, sets: int}>
     */
    public function getSetsPerDayData(): array
    {
        $startDate = now()->subDays($this->daysToShow)->startOfDay();

        return Set::query()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as sets')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($item) => [
                'date' => $item->date,
                'sets' => (int) $item->sets,
            ])
            ->toArray();
    }

    /**
     * Get summary statistics
     *
     * @return array{
     *     totalWorkouts: int,
     *     totalVolume: float,
     *     avgVolume: float,
     *     maxVolume: float
     * }
     */
    public function getSummaryStats(): array
    {
        $startDate = now()->subDays($this->daysToShow)->startOfDay();

        $totalWorkouts = Workout::where('created_at', '>=', $startDate)->count();

        $volumeStats = Set::query()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('SUM(weight * reps) as total, AVG(weight * reps) as avg, MAX(weight * reps) as max')
            ->first();

        return [
            'totalWorkouts' => $totalWorkouts,
            'totalVolume' => (float) ($volumeStats->total ?? 0),
            'avgVolume' => (float) ($volumeStats->avg ?? 0),
            'maxVolume' => (float) ($volumeStats->max ?? 0),
        ];
    }

    public function render(): View
    {
        return view('livewire.dashboard.workout-stats', [
            'volumeData' => $this->getVolumeData(),
            'setsPerDayData' => $this->getSetsPerDayData(),
            'stats' => $this->getSummaryStats(),
        ]);
    }
}
