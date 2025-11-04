<?php

declare(strict_types=1);

use App\Livewire\Workout\Edit;
use App\Models\Exercise;
use App\Models\Set;
use App\Models\User;
use App\Models\Workout;
use Carbon\Carbon;
use Livewire\Livewire;

test('workout edit page is displayed', function () {
    $user = User::factory()->create();
    $workout = Workout::factory()->create();

    $this->actingAs($user);

    $this->get("/workout/{$workout->uuid}/edit")->assertOk();
});

test('workout edit page requires authentication', function () {
    $workout = Workout::factory()->create();

    $this->get("/workout/{$workout->uuid}/edit")->assertRedirect('/login');
});

test('workout date can be updated', function () {
    $user = User::factory()->create();
    $workout = Workout::factory()->create([
        'created_at' => Carbon::parse('2025-01-01'),
    ]);

    $this->actingAs($user);

    Livewire::test(Edit::class, ['workout' => $workout])
        ->set('workoutDate', '2025-01-15')
        ->call('save')
        ->assertHasNoErrors();

    expect($workout->refresh()->created_at->format('Y-m-d'))->toBe('2025-01-15');
});

test('workout date is required', function () {
    $user = User::factory()->create();
    $workout = Workout::factory()->create();

    $this->actingAs($user);

    Livewire::test(Edit::class, ['workout' => $workout])
        ->set('workoutDate', '')
        ->call('save')
        ->assertHasErrors(['workoutDate']);
});

test('exercise reps can be updated', function () {
    $user = User::factory()->create();
    $workout = Workout::factory()->create();
    $exercise = Exercise::factory()->create();
    $workout->exercises()->attach($exercise, ['order' => 1]);
    $set = Set::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
        'reps' => 10,
        'weight' => 100,
        'order' => 1,
    ]);

    $this->actingAs($user);

    Livewire::test(Edit::class, ['workout' => $workout])
        ->set('exercises.0.sets.0.reps', 15)
        ->call('save')
        ->assertHasNoErrors();

    expect($set->refresh()->reps)->toBe(15);
});

test('exercise weight can be updated', function () {
    $user = User::factory()->create();
    $workout = Workout::factory()->create();
    $exercise = Exercise::factory()->create();
    $workout->exercises()->attach($exercise, ['order' => 1]);
    $set = Set::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
        'reps' => 10,
        'weight' => 100,
        'order' => 1,
    ]);

    $this->actingAs($user);

    Livewire::test(Edit::class, ['workout' => $workout])
        ->set('exercises.0.sets.0.weight', 125.5)
        ->call('save')
        ->assertHasNoErrors();

    expect($set->refresh()->weight)->toBe('125.50');
});

test('reps must be at least 1', function () {
    $user = User::factory()->create();
    $workout = Workout::factory()->create();
    $exercise = Exercise::factory()->create();
    $workout->exercises()->attach($exercise, ['order' => 1]);
    $set = Set::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
        'reps' => 10,
        'order' => 1,
    ]);

    $this->actingAs($user);

    Livewire::test(Edit::class, ['workout' => $workout])
        ->set('exercises.0.sets.0.reps', 0)
        ->call('save')
        ->assertHasErrors(['exercises.0.sets.0.reps']);
});

test('weight cannot be negative', function () {
    $user = User::factory()->create();
    $workout = Workout::factory()->create();
    $exercise = Exercise::factory()->create();
    $workout->exercises()->attach($exercise, ['order' => 1]);
    $set = Set::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise->id,
        'reps' => 10,
        'order' => 1,
    ]);

    $this->actingAs($user);

    Livewire::test(Edit::class, ['workout' => $workout])
        ->set('exercises.0.sets.0.weight', -10)
        ->call('save')
        ->assertHasErrors(['exercises.0.sets.0.weight']);
});

test('redirects to workout page after save', function () {
    $user = User::factory()->create();
    $workout = Workout::factory()->create();

    $this->actingAs($user);

    Livewire::test(Edit::class, ['workout' => $workout])
        ->set('workoutDate', '2025-01-15')
        ->call('save')
        ->assertRedirect(route('workout', $workout));
});

test('redirects to workout page when cancelled', function () {
    $user = User::factory()->create();
    $workout = Workout::factory()->create();

    $this->actingAs($user);

    Livewire::test(Edit::class, ['workout' => $workout])
        ->call('cancel')
        ->assertRedirect(route('workout', $workout));
});

test('can update multiple exercises and sets', function () {
    $user = User::factory()->create();
    $workout = Workout::factory()->create();

    $exercise1 = Exercise::factory()->create();
    $exercise2 = Exercise::factory()->create();

    $workout->exercises()->attach($exercise1, ['order' => 1]);
    $workout->exercises()->attach($exercise2, ['order' => 2]);

    $set1 = Set::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise1->id,
        'reps' => 10,
        'weight' => 100,
        'order' => 1,
    ]);

    $set2 = Set::factory()->create([
        'workout_id' => $workout->id,
        'exercise_id' => $exercise2->id,
        'reps' => 12,
        'weight' => 50,
        'order' => 1,
    ]);

    $this->actingAs($user);

    Livewire::test(Edit::class, ['workout' => $workout])
        ->set('exercises.0.sets.0.reps', 15)
        ->set('exercises.0.sets.0.weight', 110)
        ->set('exercises.1.sets.0.reps', 20)
        ->set('exercises.1.sets.0.weight', 60)
        ->call('save')
        ->assertHasNoErrors();

    expect($set1->refresh()->reps)->toBe(15);
    expect($set1->weight)->toBe('110.00');
    expect($set2->refresh()->reps)->toBe(20);
    expect($set2->weight)->toBe('60.00');
});
