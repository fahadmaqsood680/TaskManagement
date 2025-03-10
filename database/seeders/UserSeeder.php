<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
        ]);

        // Get Admin ID
        $adminId = $admin->id;

        // Create Manager User with added_by referencing the admin's ID
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('123'),
            'role' => 'manager',
            'added_by' => $adminId, // Assigning admin's ID as added_by
        ]);

        // Create Regular User with added_by referencing the admin's ID
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('123'),
            'role' => 'user',
            'added_by' => $adminId, // Assigning admin's ID as added_by
        ]);
    }
}
