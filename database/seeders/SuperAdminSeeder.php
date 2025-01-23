<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;



class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=> 'admin',
            'email'=> 'control@raslordeckltd.com',
            'password'=> 'Taribi4YOU',
            'role'=> 'super-admin'
        ]);
    }
}
