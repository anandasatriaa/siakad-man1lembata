<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'), // Password terenkripsi
                'level' => '1',
            ]
        );

        // Kesiswaan
        User::updateOrCreate(
            ['email' => 'kesiswaan@example.com'],
            [
                'name' => 'Kesiswaan',
                'password' => Hash::make('kesiswaan123'), // Password terenkripsi
                'level' => '2',
            ]
        );
    }
}
