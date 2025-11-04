<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\WorkoutObserver;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $uuid
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 */
#[ObservedBy(WorkoutObserver::class)]
final class Workout extends Model
{
    /** @use HasFactory<\Database\Factories\WorkoutFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsToMany<Exercise, $this>
     */
    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class)
            ->withPivot('order')
            ->withTimestamps()
            ->orderBy('exercise_workout.order');
    }

    /**
     * @return HasMany<Set, $this>
     */
    public function sets(): HasMany
    {
        return $this->hasMany(Set::class);
    }
}
