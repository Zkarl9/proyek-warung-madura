<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin StockVision',
            'email' => 'admin@stockvision.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Pemilik Warung',
            'email' => 'owner@stockvision.test',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'phone' => '6281234567890',
        ]);

        // Create additional test users
        User::factory(5)->create(['role' => 'owner']);
    }
}
