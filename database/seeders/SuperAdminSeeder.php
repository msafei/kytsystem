<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'id' => Str::uuid(),
            'employee_id' => null,
            'status_id' => 1,
            'username' => 'superadmin',
            'password' => Hash::make('superadmin', ['rounds' => 15, 'memory' => 1024, 'time' => 2]),
            'role' => 1,
        ]);
    }
}
