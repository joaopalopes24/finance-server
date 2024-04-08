<?php

namespace Database\Factories;

use App\Enum\OperationEnum;
use App\Enum\StatusEnum;
use App\Models\AccountPlan;
use App\Models\CostCenter;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'cost_center_id' => CostCenter::factory(),
            'account_plan_id' => AccountPlan::factory(),
            'description' => $this->faker->sentence(),
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'date' => $this->faker->dateTimeThisYear(),
            'operation' => Arr::random(OperationEnum::cases()),
            'status' => Arr::random(StatusEnum::cases()),
        ];
    }

    /**
     * Get a random cost center.
     */
    public function costCenter(): self
    {
        return $this->state(fn () => [
            'cost_center_id' => CostCenter::inRandomOrder()->first()->id,
        ]);
    }

    /**
     * Get a random account plan.
     */
    public function accountPlan(): self
    {
        return $this->state(fn () => [
            'account_plan_id' => AccountPlan::inRandomOrder()->first()->id,
        ]);
    }
}
