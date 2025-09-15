<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            UserRolePermissionSeeder::class,
            PostSeeder::class,
            ServiceSeeder::class,
            UserSeeder::class,
            SpecialistSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
