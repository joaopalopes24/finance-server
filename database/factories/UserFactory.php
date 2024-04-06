<?php

namespace Database\Factories;

use App\Models\User;
use App\Support\TwoFactor\RecoveryCode;
use App\Support\TwoFactor\TwoFactorAuthentication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => Carbon::now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state([
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model has two factor.
     */
    public function twoFactor(): static
    {
        return $this->secret()->confirmed()->recoveryCodes();
    }

    /**
     * Indicate that the model has two factor secret.
     */
    public function secret(): static
    {
        return $this->state([
            'two_factor_secret' => app(TwoFactorAuthentication::class)->generateSecretKey(),
        ]);
    }

    /**
     * Indicate that the model has two factor confirmed.
     */
    public function confirmed(): static
    {
        return $this->state([
            'two_factor_confirmed_at' => Carbon::now(),
        ]);
    }

    /**
     * Indicate that the model has two factor recovery codes.
     */
    public function recoveryCodes(): static
    {
        return $this->state([
            'two_factor_recovery_codes' => RecoveryCode::generateMany(),
        ]);
    }
}
