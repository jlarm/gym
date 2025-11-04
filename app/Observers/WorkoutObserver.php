<?php

namespace App\Observers;

use App\Models\Workout;
use Illuminate\Support\Str;

class WorkoutObserver
{
    public function creating(Workout $workout): string
    {
        return $workout->uuid = (string) Str::uuid();
    }
}
