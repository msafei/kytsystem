<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
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
        // Seed Superadmin User
        User::create([
            'employee_id' => null,
            'status_id' => 1,
            'username' => 'superadmin',
            'password' => Hash::make('superadmin'),
            'role' => 0
        ]);

        // Insert Companies (Bahasa Indonesia)
        DB::table('companies')->insert([
            ['name' => 'PT Nippon Shokubai Indonesia', 'companyType' => 1, 'status' => 1],
            ['name' => 'PT Karya Teknik Utama', 'companyType' => 2, 'status' => 1], // Perusahaan Jasa Maintenance dan Sipil
        ]);

        // Insert Departments (Bahasa Inggris)
        DB::table('departments')->insert([
            ['name' => 'Human Resources'],
            ['name' => 'Finance'],
            ['name' => 'Information Technology'],
            ['name' => 'Marketing'],
            ['name' => 'Sales'],
            ['name' => 'Operations'],
            ['name' => 'Logistics'],
            ['name' => 'Research & Development'],
            ['name' => 'Customer Support'],
            ['name' => 'Legal'],
        ]);

        // Insert Positions (Bahasa Inggris)
        DB::table('positions')->insert([
            // CompanyType = 1 (PT Nippon Shokubai Indonesia)
            ['name' => 'Manager', 'companyType' => 1],
            ['name' => 'Department Head', 'companyType' => 1],
            ['name' => 'Supervisor', 'companyType' => 1],
            ['name' => 'Staff PIC', 'companyType' => 1],
            ['name' => 'Safety Department', 'companyType' => 1],

            // CompanyType = 2 (PT Karya Teknik Utama - Jasa Maintenance & Sipil)
            ['name' => 'Supervisor', 'companyType' => 2],
            ['name' => 'Safety Officer', 'companyType' => 2],
            ['name' => 'Mekanik', 'companyType' => 2],
            ['name' => 'Teknisi Listrik', 'companyType' => 2],
            ['name' => 'Operator Alat Berat', 'companyType' => 2],
            ['name' => 'Mandor Sipil', 'companyType' => 2],
            ['name' => 'Tukang Bangunan', 'companyType' => 2],
            ['name' => 'Welder (Las)', 'companyType' => 2],
        ]);

        // Ambil ID PT Nippon Shokubai Indonesia (CompanyType = 1)
        $company1 = DB::table('companies')->where('companyType', 1)->first();
        $department1 = DB::table('departments')->inRandomOrder()->first(); // Semua departemen sama

        // Ambil ID semua posisi dengan CompanyType = 1
        $positions1 = DB::table('positions')->where('companyType', 1)->get();

        // Nama khas Indonesia untuk karyawan PT Nippon Shokubai Indonesia
        $namesCompany1 = [
            "Budi Santoso", "Siti Aminah", "Agus Setiawan", "Rini Kusuma", "Dedi Supriyadi",
            "Nurul Hidayat", "Teguh Wibowo", "Ratna Dewi", "Eko Prasetyo", "Sri Wahyuni"
        ];

        // Insert Employees for PT Nippon Shokubai Indonesia
        $i = 0;
        foreach ($positions1 as $position) {
            Employee::create([
                'user_id' => null,
                'nik' => fake()->unique()->randomNumber(9, true),
                'name' => $namesCompany1[$i++ % count($namesCompany1)], // Menggunakan nama khas Indonesia
                'position_id' => $position->id,
                'company_id' => $company1->id,
                'department_id' => $department1->id,
                'status' => 1,
            ]);
        }

        // Ambil PT Karya Teknik Utama (CompanyType = 2)
        $company2 = DB::table('companies')->where('companyType', 2)->first();
        $positions2 = DB::table('positions')->where('companyType', 2)->get();

        // Nama khas Indonesia untuk karyawan PT Karya Teknik Utama
        $namesCompany2 = [
            "Ahmad Fauzi", "Dian Saputra", "Indra Lesmana", "Yulianto", "Bambang Suharto",
            "Cahyo Pranoto", "Samsul Bahri", "Rizky Ramadhan", "Gunawan", "Sukiman"
        ];

        // Insert Employees for PT Karya Teknik Utama (CompanyType = 2) dengan posisi berbeda
        $i = 0;
        foreach ($positions2 as $position) {
            Employee::create([
                'user_id' => null,
                'nik' => fake()->unique()->randomNumber(9, true),
                'name' => $namesCompany2[$i++ % count($namesCompany2)], // Menggunakan nama khas Indonesia
                'position_id' => $position->id,
                'company_id' => $company2->id,
                'department_id' => null, // CompanyType = 2 memiliki department NULL
                'status' => 1,
            ]);
        }
    }
}
