<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Construction Materials', 'code_prefix' => 'CON', 'description' => 'Materials used in construction projects'],
            ['name' => 'Networking Equipment', 'code_prefix' => 'NET', 'description' => 'Routers, switches, cables, and networking hardware'],
            ['name' => 'Internet Installation Tools', 'code_prefix' => 'IIT', 'description' => 'Tools and equipment for internet installations'],
            ['name' => 'Solar Equipment', 'code_prefix' => 'SOL', 'description' => 'Solar panels, inverters, batteries, and related equipment'],
            ['name' => 'IT Equipment', 'code_prefix' => 'IT', 'description' => 'Computers, laptops, servers, and IT hardware'],
            ['name' => 'Office Supplies', 'code_prefix' => 'OFF', 'description' => 'General office supplies and stationery'],
            ['name' => 'Electrical Supplies', 'code_prefix' => 'ELC', 'description' => 'Electrical components, wires, and fixtures'],
            ['name' => 'Safety Equipment', 'code_prefix' => 'SAF', 'description' => 'Safety gear and protective equipment'],
            ['name' => 'Tools', 'code_prefix' => 'TOL', 'description' => 'Hand tools and power tools'],
            ['name' => 'Consumables', 'code_prefix' => 'CONS', 'description' => 'Consumable items and supplies'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                [
                    'code_prefix' => $category['code_prefix'],
                    'description' => $category['description']
                ]
            );
        }
    }
}
