<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@kampus.ac.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Akun Dosen
        User::updateOrCreate(
            ['email' => 'dosen@kampus.ac.id'],
            [
                'name' => 'Dosen Kampus',
                'password' => Hash::make('dosen12345'),
                'role' => 'dosen',
                'email_verified_at' => now(),
            ]
        );

        // Akun Mahasiswa
        User::updateOrCreate(
            ['email' => 'mahasiswa@kampus.ac.id'],
            [
                'name' => 'Mahasiswa Kampus',
                'password' => Hash::make('mahasiswa12345'),
                'role' => 'mahasiswa',
                'email_verified_at' => now(),
            ]
        );
    }
}
