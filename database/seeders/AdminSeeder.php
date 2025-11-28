<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure admin role exists
        $adminRole = Role::firstOrCreate(['name' => 'super-admin']);

        // Create or update admin users
        $admins = [
            [
                'name' => 'Frank',
                'email' => 'frank@raslordeckltd.com',
                'password' => 'admin@2025',
            ],
            [
                'name' => 'T. Isaac',
                'email' => 't.isaac@raslordeckltd.com',
                'password' => 'admin@2025',
            ],
        ];

        foreach ($admins as $adminData) {
            $user = User::firstOrCreate(
                ['email' => $adminData['email']],
                [
                    'name' => $adminData['name'],
                    'password' => Hash::make($adminData['password']),
                ]
            );

            // Update password if user already exists (in case password changed)
            if ($user->wasRecentlyCreated === false) {
                $user->password = Hash::make($adminData['password']);
                $user->save();
            }

            // Assign admin role (sync to ensure only admin role)
            $user->syncRoles(['admin']);

            echo "✅ Admin user created/updated: {$adminData['email']}\n";
        }
    }
}
