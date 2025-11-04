<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <flux:card>
            <flux:heading size="sm" class="mb-2">Total Workouts</flux:heading>
            <div class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['totalWorkouts'] }}</div>
            <flux:text size="sm" class="text-zinc-500">Last {{ $daysToShow }} days</flux:text>
        </flux:card>

        <flux:card>
            <flux:heading size="sm" class="mb-2">Total Volume</flux:heading>
            <div class="text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['totalVolume']) }}</div>
            <flux:text size="sm" class="text-zinc-500">lbs lifted</flux:text>
        </flux:card>

        <flux:card>
            <flux:heading size="sm" class="mb-2">Avg Volume/Day</flux:heading>
            <div class="text-3xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['avgVolume']) }}</div>
            <flux:text size="sm" class="text-zinc-500">lbs per workout</flux:text>
        </flux:card>
    </div>

    <flux:card>
        <div class="mb-4">
            <flux:heading size="lg">Volume Over Time</flux:heading>
            <flux:text size="sm" class="text-zinc-500">Total weight lifted per day (lbs)</flux:text>
        </div>

        @if(count($volumeData) > 0)
            <flux:chart :value="$volumeData" class="h-64">
                <flux:chart.svg>
                    <flux:chart.area field="volume" class="text-blue-500/20" />
                    <flux:chart.line field="volume" class="text-blue-500" />
                    <flux:chart.point field="volume" class="text-blue-500" />

                    <flux:chart.axis axis="x" field="date" :format="['month' => 'short', 'day' => 'numeric']">
                        <flux:chart.axis.tick />
                    </flux:chart.axis>

                    <flux:chart.axis axis="y" :format="['notation' => 'compact']">
                        <flux:chart.axis.grid />
                        <flux:chart.axis.tick />
                    </flux:chart.axis>

                    <flux:chart.cursor />
                </flux:chart.svg>

                <flux:chart.tooltip>
                    <flux:chart.tooltip.heading field="date" :format="['month' => 'long', 'day' => 'numeric', 'year' => 'numeric']" />
                    <flux:chart.tooltip.value field="volume" label="Volume" suffix=" lbs" />
                </flux:chart.tooltip>
            </flux:chart>
        @else
            <div class="text-center py-12 text-zinc-500">
                <flux:icon.chart-bar class="size-12 mx-auto mb-2" />
                <flux:text>No workout data available for the selected period</flux:text>
            </div>
        @endif
    </flux:card>

    <flux:card>
        <div class="mb-4">
            <flux:heading size="lg">Sets Per Day</flux:heading>
            <flux:text size="sm" class="text-zinc-500">Total number of sets completed each day</flux:text>
        </div>

        @if(count($setsPerDayData) > 0)
            <flux:chart :value="$setsPerDayData" class="h-48">
                <flux:chart.svg>
                    <flux:chart.area field="sets" class="text-green-500/20" />
                    <flux:chart.line field="sets" class="text-green-500" />
                    <flux:chart.point field="sets" class="text-green-500" />

                    <flux:chart.axis axis="x" field="date" :format="['month' => 'short', 'day' => 'numeric']">
                        <flux:chart.axis.tick />
                    </flux:chart.axis>

                    <flux:chart.axis axis="y">
                        <flux:chart.axis.grid />
                        <flux:chart.axis.tick />
                    </flux:chart.axis>

                    <flux:chart.cursor />
                </flux:chart.svg>

                <flux:chart.tooltip>
                    <flux:chart.tooltip.heading field="date" :format="['month' => 'long', 'day' => 'numeric']" />
                    <flux:chart.tooltip.value field="sets" label="Sets" suffix=" sets" />
                </flux:chart.tooltip>
            </flux:chart>
        @else
            <div class="text-center py-12 text-zinc-500">
                <flux:icon.chart-bar class="size-12 mx-auto mb-2" />
                <flux:text>No workout data available for the selected period</flux:text>
            </div>
        @endif
    </flux:card>
</div>
