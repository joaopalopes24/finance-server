<?php

namespace App\Providers;

use App\Support\Macros;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Macros::register();

        $this->bootGates();

        $this->bootPassword();

        Model::shouldBeStrict(! $this->app->isProduction());

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return frontend("/reset-password?token={$token}&email={$notifiable->email}");
        });
    }

    /**
     * Configure the password requirements for the application.
     */
    private function bootPassword(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8)->max(64);

            return $this->app->isProduction() ? $rule->letters()->numbers()->uncompromised() : $rule;
        });
    }

    /**
     * Configure the gates for the application.
     */
    private function bootGates(): void
    {
        Gate::define('two-factor-is-enabled', function ($user) {
            return $user->two_factor_secret && ! $user->two_factor_confirmed_at;
        });

        Gate::define('two-factor-is-confirmed', function ($user) {
            return $user->two_factor_secret && $user->two_factor_confirmed_at;
        });

        Gate::define('two-factor-is-disabled', function ($user) {
            return ! $user->two_factor_secret || ! $user->two_factor_confirmed_at;
        });
    }
}
