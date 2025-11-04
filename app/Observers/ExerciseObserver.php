<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Exercise;
use Illuminate\Support\Str;

final class ExerciseObserver
{
    public function creating(Exercise $exercise): string
    {
        return $exercise->uuid = (string) Str::uuid();
    }
}
