<?php

namespace App\Providers;

use App\Support\Macros;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Model;
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
}
