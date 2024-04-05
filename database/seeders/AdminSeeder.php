<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = config('system.admin');

        $user = User::firstOrNew([
            'email' => $admin['email']
        ],[
            'name' => $admin['name'],
            'password' => $admin['password'],
        ]);

        $user->save();
    }
}
