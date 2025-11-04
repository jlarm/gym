<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Workout;
use Illuminate\Support\Str;

final class WorkoutObserver
{
    public function creating(Workout $workout): string
    {
        return $workout->uuid = (string) Str::uuid();
    }
}
