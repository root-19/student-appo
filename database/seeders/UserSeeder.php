<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Registrar Staff',
                'email' => 'registrar@ptc.edu.ph',
                'password' => Hash::make('registrar123'),
                'role' => 'Registrar',
                'department' => 'Registrar Office',
                'status' => 'Active',
            ],
            [
                'name' => 'Admin Staff',
                'email' => 'admin@ptc.edu.ph',
                'password' => Hash::make('admin123'),
                'role' => 'Admin',
                'department' => 'Student Affairs',
                'status' => 'Active',
            ],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@ptc.edu.ph',
                'password' => Hash::make('superadmin123'),
                'role' => 'SuperAdmin',
                'status' => 'Active',
            ],
            [
                'name' => 'Academic Staff',
                'email' => 'academic@ptc.edu.ph',
                'password' => Hash::make('academic123'),
                'role' => 'Academic',
                'department' => 'Academic Affairs',
                'status' => 'Active',
            ],
            [
                'name' => 'Hakim Maulay',
                'email' => 'hamaulay@ptc.edu.ph',
                'password' => Hash::make('maulay12345'),
                'role' => 'Student',
                'program' => 'CCS',
                'section' => '1A',
                'student_id' => '2024-1004',
                'year_level' => '1st Year',
                'status' => 'Active',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
