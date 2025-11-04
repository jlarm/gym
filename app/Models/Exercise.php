<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\ExerciseObserver;
use Carbon\CarbonImmutable;
use Database\Factories\ExerciseFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 */
#[ObservedBy(ExerciseObserver::class)]
final class Exercise extends Model
{
    /** @use HasFactory<ExerciseFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'uuid' => 'string',
            'name' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsToMany<Workout, $this>
     */
    public function workouts(): BelongsToMany
    {
        return $this->belongsToMany(Workout::class)
            ->withPivot('order')
            ->withTimestamps();
    }

    /**
     * @return HasMany<Set, $this>
     */
    public function sets(): HasMany
    {
        return $this->hasMany(Set::class);
    }
}
