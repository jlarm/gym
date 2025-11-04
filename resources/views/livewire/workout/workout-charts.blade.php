<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <flux:card>
            <flux:heading size="sm" class="mb-2">Total Volume</flux:heading>
            <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['totalVolume']) }}</div>
            <flux:text size="sm" class="text-zinc-500">lbs</flux:text>
        </flux:card>

        <flux:card>
            <flux:heading size="sm" class="mb-2">Total Sets</flux:heading>
            <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['totalSets'] }}</div>
            <flux:text size="sm" class="text-zinc-500">sets</flux:text>
        </flux:card>

        <flux:card>
            <flux:heading size="sm" class="mb-2">Avg Weight</flux:heading>
            <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['avgWeight'], 1) }}</div>
            <flux:text size="sm" class="text-zinc-500">lbs</flux:text>
        </flux:card>

        <flux:card>
            <flux:heading size="sm" class="mb-2">Max Weight</flux:heading>
            <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($stats['maxWeight']) }}</div>
            <flux:text size="sm" class="text-zinc-500">lbs</flux:text>
        </flux:card>
    </div>

    @if(count($exerciseVolumeData) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <flux:card>
                <div class="mb-4">
                    <flux:heading size="lg">Volume by Exercise</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">Total weight x reps per exercise</flux:text>
                </div>

                <flux:chart :value="$exerciseVolumeData" class="h-64">
                    <flux:chart.svg>
                        <flux:chart.area field="volume" class="text-purple-500/20" />
                        <flux:chart.line field="volume" class="text-purple-500" />
                        <flux:chart.point field="volume" class="text-purple-500" />

                        <flux:chart.axis axis="x" field="exercise">
                            <flux:chart.axis.tick />
                        </flux:chart.axis>

                        <flux:chart.axis axis="y" :format="['notation' => 'compact']">
                            <flux:chart.axis.grid />
                            <flux:chart.axis.tick />
                        </flux:chart.axis>

                        <flux:chart.cursor />
                    </flux:chart.svg>

                    <flux:chart.tooltip>
                        <flux:chart.tooltip.heading field="exercise" />
                        <flux:chart.tooltip.value field="volume" label="Volume" suffix=" lbs" />
                    </flux:chart.tooltip>
                </flux:chart>
            </flux:card>

            <flux:card>
                <div class="mb-4">
                    <flux:heading size="lg">Sets by Exercise</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">Number of sets per exercise</flux:text>
                </div>

                <flux:chart :value="$exerciseSetsData" class="h-64">
                    <flux:chart.svg>
                        <flux:chart.line field="sets" class="text-orange-500" curve="none" />
                        <flux:chart.point field="sets" class="text-orange-500" />

                        <flux:chart.axis axis="x" field="exercise">
                            <flux:chart.axis.tick />
                        </flux:chart.axis>

                        <flux:chart.axis axis="y">
                            <flux:chart.axis.grid />
                            <flux:chart.axis.tick />
                        </flux:chart.axis>

                        <flux:chart.cursor />
                    </flux:chart.svg>

                    <flux:chart.tooltip>
                        <flux:chart.tooltip.heading field="exercise" />
                        <flux:chart.tooltip.value field="sets" label="Sets" />
                    </flux:chart.tooltip>
                </flux:chart>
            </flux:card>
        </div>

    @else
        <flux:card>
            <div class="text-center py-12 text-zinc-500">
                <flux:icon.chart-bar class="size-16 mx-auto mb-4" />
                <flux:heading size="lg" class="mb-2">No Exercise Data</flux:heading>
                <flux:text>Add exercises to this workout to see charts</flux:text>
            </div>
        </flux:card>
    @endif
</div>
