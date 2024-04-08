<?php

namespace Database\Seeders;

use App\Models\AccountPlan;
use Illuminate\Database\Seeder;

class AccountPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AccountPlan::factory()->count(4)->create();
    }
}
