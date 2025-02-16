<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'employee_id' => null,
            'status_id' => 1,
            'username' => 'superadmin',
            'password' => Hash::make('superadmin'), // Pastikan Hash::make() hanya dilakukan di sini
            'role' => 1,
            'remember_token' => null
        ]);
    }
}

