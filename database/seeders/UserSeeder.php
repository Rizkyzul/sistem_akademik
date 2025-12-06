<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mahasiswa; // Import model Mahasiswa
use App\Models\Dosen;     // Import model Dosen

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // 1. Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'), // password
            'role' => 'admin',
        ]);

        // 2. Create Mahasiswa User
        User::create([
            'name' => 'Mahasiswa User',
            'email' => 'mahasiswa@gmail.com',
            'nim' => '1234567890', // NIM for Mahasiswa
            'password' => Hash::make('password123'), // password
            'role' => 'mahasiswa',
        ]);

        // 3. Create Dosen User
        User::create([
            'name' => 'Dosen User',
            'email' => 'dosen@gmail.com',
            'password' => Hash::make('password123'), // password
            'role' => 'dosen',
        ]);
    }
}