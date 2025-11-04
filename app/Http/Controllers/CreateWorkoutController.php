<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Workout;

final class CreateWorkoutController extends Controller
{
    public function __invoke()
    {
        $workout = Workout::create();

        return redirect()->route('workout', $workout);
    }
}
