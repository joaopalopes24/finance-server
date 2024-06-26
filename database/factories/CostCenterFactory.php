<?php

namespace Database\Factories;

use App\Models\CostCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

class CostCenterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = CostCenter::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->city(),
            'status' => $this->faker->boolean(),
        ];
    }
}
