<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
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
        // Insert 10 Companies
        DB::table('companies')->insert([
            ['name' => 'Alpha Corp', 'companyType' => 1, 'status' => 1],
            ['name' => 'Beta Industries', 'companyType' => 2, 'status' => 1],
            ['name' => 'Gamma Solutions', 'companyType' => 1, 'status' => 1],
            ['name' => 'Delta Enterprises', 'companyType' => 2, 'status' => 1],
            ['name' => 'Epsilon Tech', 'companyType' => 1, 'status' => 1],
            ['name' => 'Zeta Systems', 'companyType' => 2, 'status' => 1],
            ['name' => 'Eta Innovations', 'companyType' => 1, 'status' => 1],
            ['name' => 'Theta Corp', 'companyType' => 2, 'status' => 1],
            ['name' => 'Iota Technologies', 'companyType' => 1, 'status' => 1],
            ['name' => 'Kappa Global', 'companyType' => 2, 'status' => 1],
        ]);

        // Insert 10 Departments
        DB::table('departments')->insert([
            ['name' => 'HR'],
            ['name' => 'Finance'],
            ['name' => 'IT'],
            ['name' => 'Marketing'],
            ['name' => 'Sales'],
            ['name' => 'Operations'],
            ['name' => 'Logistics'],
            ['name' => 'R&D'],
            ['name' => 'Customer Support'],
            ['name' => 'Legal'],
        ]);

        // Insert 10 Positions
        DB::table('positions')->insert([
            ['name' => 'Manager', 'companyType' => 1, 'defaultRole' => 2],
            ['name' => 'Assistant Manager', 'companyType' => 1, 'defaultRole' => 3],
            ['name' => 'Supervisor', 'companyType' => 2, 'defaultRole' => 3],
            ['name' => 'Team Lead', 'companyType' => 1, 'defaultRole' => 4],
            ['name' => 'Senior Engineer', 'companyType' => 1, 'defaultRole' => 4],
            ['name' => 'Junior Engineer', 'companyType' => 2, 'defaultRole' => 5],
            ['name' => 'Intern', 'companyType' => 1, 'defaultRole' => 6],
            ['name' => 'HR Officer', 'companyType' => 1, 'defaultRole' => 3],
            ['name' => 'Finance Analyst', 'companyType' => 2, 'defaultRole' => 4],
            ['name' => 'Sales Executive', 'companyType' => 1, 'defaultRole' => 3],
        ]);
    }
}
