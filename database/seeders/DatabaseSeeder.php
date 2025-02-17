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
            'role' => 0
        ]);
        // Insert 10 Companies
        DB::table('companies')->insert([
            ['name' => 'PT Nippon Shokubai Indonesia', 'companyType' => 1, 'status' => 1],
            ['name' => 'PT Beta Industries', 'companyType' => 2, 'status' => 1],
            ['name' => 'PT Gamma Solutions', 'companyType' => 2, 'status' => 1],
            ['name' => 'PT Delta Enterprises', 'companyType' => 2, 'status' => 1],
            ['name' => 'PT Epsilon Tech', 'companyType' => 2, 'status' => 1],
            ['name' => 'PT Zeta Systems', 'companyType' => 2, 'status' => 1],
            ['name' => 'PT Eta Innovations', 'companyType' => 2, 'status' => 1],
            ['name' => 'PT Theta Corp', 'companyType' => 2, 'status' => 1],
            ['name' => 'PT Iota Technologies', 'companyType' => 2, 'status' => 1],
            ['name' => 'PT Kappa Global', 'companyType' => 2, 'status' => 1],
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
            ['name' => 'Manager', 'companyType' => 1, 'defaultRole' => 1],
            ['name' => 'Departement Head', 'companyType' => 1, 'defaultRole' => 1],
            ['name' => 'Supervisor', 'companyType' => 1, 'defaultRole' => 1],
            ['name' => 'Staff PIC', 'companyType' => 1, 'defaultRole' => 1],
            ['name' => 'Safety Departement', 'companyType' => 1, 'defaultRole' => 1],
            ['name' => 'Supervisor', 'companyType' => 2, 'defaultRole' => 2],
            ['name' => 'Safety Officer', 'companyType' => 2, 'defaultRole' => 2],
            ['name' => 'Staff', 'companyType' => 2, 'defaultRole' => 2],
            ['name' => 'Mechanic', 'companyType' => 2, 'defaultRole' => 2],
            ['name' => 'Skill', 'companyType' => 2, 'defaultRole' => 2],
            ['name' => 'Support', 'companyType' => 2, 'defaultRole' => 2],
        ]);
    }
}
