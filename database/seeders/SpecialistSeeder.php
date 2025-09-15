<?php

namespace Database\Seeders;

use App\Models\Specialist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SpecialistSeeder extends Seeder
{
    public function run(): void
    {
        $specialists = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@clinic.com',
                'password' => Hash::make('password'),
                'mobile' => '+1234567890',
                'type' => 'beauty',
                'bio' => 'Professional beauty specialist with 10+ years experience in hair styling and makeup.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'michael.chen@clinic.com',
                'password' => Hash::make('password'),
                'mobile' => '+1234567891',
                'type' => 'health',
                'bio' => 'Licensed health consultant specializing in nutrition and wellness.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emma Davis',
                'email' => 'emma.davis@clinic.com',
                'password' => Hash::make('password'),
                'mobile' => '+1234567892',
                'type' => 'fitness',
                'bio' => 'Certified personal trainer and fitness coach with expertise in strength training.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dr. Robert Wilson',
                'email' => 'robert.wilson@clinic.com',
                'password' => Hash::make('password'),
                'mobile' => '+1234567893',
                'type' => 'beauty',
                'bio' => 'Expert in skincare treatments and anti-aging procedures.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lisa Martinez',
                'email' => 'lisa.martinez@clinic.com',
                'password' => Hash::make('password'),
                'mobile' => '+1234567894',
                'type' => 'fitness',
                'bio' => 'Yoga instructor and meditation specialist with 8 years of experience.',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dr. James Taylor',
                'email' => 'james.taylor@clinic.com',
                'password' => Hash::make('password'),
                'mobile' => '+1234567895',
                'type' => 'health',
                'bio' => 'Mental health counselor and therapy specialist.',
                'is_active' => false,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($specialists as $specialist) {
            Specialist::create($specialist);
        }
    }
}
