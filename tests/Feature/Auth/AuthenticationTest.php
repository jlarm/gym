<?php

declare(strict_types=1);

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->withoutTwoFactor()->create();

    $response = $this->withSession(['_token' => 'test-token'])
        ->post(route('login'), [
            '_token' => 'test-token',
            'email' => $user->email,
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->withSession(['_token' => 'test-token'])
        ->post(route('login'), [
            '_token' => 'test-token',
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

    $response->assertSessionHasErrorsIn('email');

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('logout'), [
            '_token' => 'test-token',
        ]);

    $response->assertRedirect(route('home'));
    $this->assertGuest();
});
