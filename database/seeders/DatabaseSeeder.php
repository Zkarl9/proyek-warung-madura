<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalur utama untuk mengeksekusi semua seeder.
     */
    public function run(): void
    {
        // Di sini kita perintahkan Laravel untuk mengeksekusi UserSeeder milikmu
        $this->call([
            UserSeeder::class,
        ]);
    }
}