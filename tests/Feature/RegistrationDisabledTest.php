<?php

declare(strict_types=1);

use App\Models\User;

test('registration page is accessible when no users exist', function () {
    $response = $this->get('/register');

    $response->assertSuccessful();
});

test('registration page returns 403 when users exist', function () {
    User::factory()->create();

    $response = $this->get('/register');

    $response->assertForbidden();
});

test('registration post returns 403 when users exist', function () {
    User::factory()->create();

    $response = $this->withSession(['_token' => 'test-token'])
        ->post('/register', [
            '_token' => 'test-token',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertForbidden();
});

test('registration link is visible on login page when no users exist', function () {
    $response = $this->get('/login');

    $response->assertSuccessful();
    $response->assertSee('Sign up');
    $response->assertSee("Don't have an account?");
});

test('registration link is hidden on login page when users exist', function () {
    User::factory()->create();

    $response = $this->get('/login');

    $response->assertSuccessful();
    $response->assertDontSee('Sign up');
    $response->assertDontSee("Don't have an account?");
});

test('first user can register successfully', function () {
    $response = $this->withSession(['_token' => 'test-token'])
        ->post('/register', [
            '_token' => 'test-token',
            'name' => 'First User',
            'email' => 'first@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertRedirect('/dashboard');
    expect(User::count())->toBe(1);
    expect(User::first()->email)->toBe('first@example.com');
});
