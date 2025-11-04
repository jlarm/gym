# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application using Livewire 3 (with Livewire Flux and Volt) for building reactive components. The project is a gym/workout management application built on Laravel's Livewire starter kit with authentication powered by Laravel Fortify.

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Livewire 3 with Flux components and Volt
- **Styling**: Tailwind CSS v4 with Vite
- **Authentication**: Laravel Fortify with two-factor authentication support
- **Testing**: Pest PHP (PHPUnit alternative)
- **Development Server**: Laravel Herd (implied from directory structure)

## Development Commands

### Initial Setup
```bash
composer setup
# Runs: composer install, copies .env, generates key, runs migrations, npm install, npm run build
```

### Development Server
```bash
composer dev
# Starts: PHP server, queue worker, log viewer (pail), and Vite dev server concurrently
```

Or run services individually:
```bash
php artisan serve           # Development server at http://localhost:8000
php artisan queue:listen    # Queue worker
php artisan pail           # Real-time log viewer
npm run dev                # Vite dev server with hot reload
```

### Testing
```bash
composer test              # Clear config cache and run all tests
php artisan test           # Run all Pest tests
php artisan test --filter=TestName  # Run specific test
```

### Code Quality
```bash
./vendor/bin/pint          # Laravel Pint code formatter (PSR-12)
./vendor/bin/pint --test   # Check formatting without fixing
```

### Database
```bash
php artisan migrate                    # Run migrations
php artisan migrate:fresh --seed      # Fresh migration with seeders
php artisan make:migration name       # Create new migration
```

### Asset Building
```bash
npm run build              # Production build
npm run dev               # Development with hot reload
```

## Architecture

### Livewire Component Structure

This application uses **class-based Livewire components** located in `app/Livewire/` with corresponding views in `resources/views/livewire/`.

**Component Pattern:**
- Components are namespaced by feature (e.g., `App\Livewire\Settings\Profile`)
- Views follow the namespace structure (e.g., `resources/views/livewire/settings/profile.blade.php`)
- Components are registered in routes using class references: `Route::get('path', ComponentClass::class)`

**Key Livewire Directories:**
- `app/Livewire/Settings/` - User settings components (Profile, Password, TwoFactor, Appearance)
- `app/Livewire/Actions/` - Action-based components
- `resources/views/livewire/` - Livewire component views

### Authentication Flow

**Laravel Fortify** handles all authentication with these features enabled:
- User registration
- Password reset
- Email verification
- Two-factor authentication (with password confirmation)

**Authentication Routes:**
- Login/Register handled automatically by Fortify
- Post-auth redirect: `/dashboard`
- Settings routes (auth required): `/settings/profile`, `/settings/password`, `/settings/two-factor`, `/settings/appearance`

**Key Files:**
- `config/fortify.php` - Fortify configuration
- `app/Models/User.php` - User model with two-factor support
- Fortify views are Livewire components in `app/Livewire/`

### View Structure

- `resources/views/components/` - Reusable Blade components
- `resources/views/flux/` - Livewire Flux component overrides
- `resources/views/partials/` - Partial views
- `resources/views/livewire/` - Livewire component views
- `resources/views/dashboard.blade.php` - Main dashboard
- `resources/views/welcome.blade.php` - Public homepage

### Frontend Assets

Vite configuration in `vite.config.js`:
- Entry points: `resources/css/app.css`, `resources/js/app.js`
- Tailwind CSS v4 plugin enabled
- Hot reload enabled for all Blade files

## Database

Uses SQLite by default (see `phpunit.xml` and typical Laravel Herd setup).

**Migrations:**
- Standard Laravel migrations in `database/migrations/`
- Includes user authentication tables
- Two-factor authentication columns added to users table
- Workouts table (recently added)

**Models:**
- `app/Models/User.php` - User model with Fortify traits
- `app/Models/Workout.php` - Workout model (new feature in progress)

## Testing Configuration

**Pest PHP** is configured with:
- Test suites: `Unit` and `Feature`
- SQLite in-memory database for testing
- Array-based cache and session drivers
- Synchronous queue processing

**Test directories:**
- `tests/Feature/` - Feature tests
- `tests/Unit/` - Unit tests

## Important Conventions

1. **Livewire Components**: Always create matching view files when adding new components
2. **Routes**: Use class-based route registration for Livewire: `Route::get('path', Component::class)`
3. **Fortify Integration**: Authentication views/logic should integrate with Livewire, not use traditional Blade forms
4. **Two-Factor Auth**: Settings require password confirmation (configured in Fortify)
5. **Component Events**: Use `$this->dispatch('event-name')` for Livewire component communication

## Livewire Flux

This project uses **Livewire Flux** for pre-built UI components. Flux components are invoked with the `<flux:*>` prefix in Blade templates. Custom overrides can be placed in `resources/views/flux/`.
