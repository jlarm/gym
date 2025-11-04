<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\SetObserver;
use Carbon\CarbonImmutable;
use Database\Factories\SetFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $uuid
 * @property int $workout_id
 * @property int $exercise_id
 * @property int $reps
 * @property float|null $weight
 * @property int $order
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property-read Workout $workout
 * @property-read Exercise $exercise
 */
#[ObservedBy(SetObserver::class)]
final class Set extends Model
{
    /** @use HasFactory<SetFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'workout_id' => 'integer',
            'exercise_id' => 'integer',
            'reps' => 'integer',
            'weight' => 'decimal:2',
            'order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Workout, $this>
     */
    public function workout(): BelongsTo
    {
        return $this->belongsTo(Workout::class);
    }

    /**
     * @return BelongsTo<Exercise, $this>
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
