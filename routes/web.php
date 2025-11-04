<?php

declare(strict_types=1);

use App\Http\Controllers\CreateWorkoutController;
use App\Http\Controllers\IndexWorkoutController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorRecoveryCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->middleware('registration.disabled')
        ->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('registration.disabled');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Password Reset routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Email Verification routes
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// Password Confirmation routes
Route::middleware(['auth'])->group(function () {
    Route::get('/user/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store'])->name('password.confirm.store');
    Route::get('/user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])->name('password.confirmation');
});

// Two Factor Authentication routes
Route::middleware(['auth'])->group(function () {
    Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store']);
    Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy']);
    Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show']);
    Route::get('/user/two-factor-secret-key', [TwoFactorSecretKeyController::class, 'show']);
    Route::get('/user/two-factor-recovery-codes', [TwoFactorRecoveryCodeController::class, 'index']);
    Route::post('/user/two-factor-recovery-codes', [TwoFactorRecoveryCodeController::class, 'store']);
});

Route::middleware('guest')->group(function () {
    Route::get('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])->name('two-factor.login');
    Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store']);
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('workout/create', CreateWorkoutController::class)
    ->middleware(['auth', 'verified'])
    ->name('workout.create');

Route::get('workout/{workout:uuid}', IndexWorkoutController::class)
    ->middleware(['auth', 'verified'])
    ->name('workout');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
