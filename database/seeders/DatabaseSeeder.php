<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;  // Ensure correct import

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles if they don't exist
        Role::firstOrCreate(['name' => 'super-admin']);
        Role::firstOrCreate(['name' => 'admin']);

        // Create super-admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'control@raslordeckltd.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('Rodsecure@2025'),  // Change this to a secure password
            ]
        );

        // Assign super-admin role to the user
        $superAdmin->assignRole('super-admin');
    }
}
