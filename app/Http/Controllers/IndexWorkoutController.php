<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Workout;

final class IndexWorkoutController extends Controller
{
    public function __invoke(Workout $workout)
    {
        return view('workout', [
            'workout' => $workout,
        ]);
    }
}
