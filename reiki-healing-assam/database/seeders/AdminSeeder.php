<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@reikihealingassam.com'],
            [
                'name'     => 'Dr. Intajur Rahman',
                'email'    => 'admin@reikihealingassam.com',
                'password' => Hash::make('Admin@1234'),
                'role'     => 'admin',
                'is_active' => true,
            ]
        );
    }
}
