<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Specialist;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $specialists = Specialist::all();

        $services = [
            // Beauty Services
            [
                'specialist_id' => $specialists->where('type', 'beauty')->first()->id,
                'name' => 'Haircut & Styling',
                'description' => 'Professional haircut and styling service',
                'price' => 50.00,
                'duration' => 60,
                'type' => 'beauty',
                'is_active' => true,
            ],
            [
                'specialist_id' => $specialists->where('type', 'beauty')->first()->id,
                'name' => 'Hair Coloring',
                'description' => 'Professional hair coloring service',
                'price' => 120.00,
                'duration' => 120,
                'type' => 'beauty',
                'is_active' => true,
            ],
            [
                'specialist_id' => $specialists->where('type', 'beauty')->skip(1)->first()->id,
                'name' => 'Facial Treatment',
                'description' => 'Deep cleansing facial treatment',
                'price' => 80.00,
                'duration' => 90,
                'type' => 'beauty',
                'is_active' => true,
            ],
            [
                'specialist_id' => $specialists->where('type', 'beauty')->skip(1)->first()->id,
                'name' => 'Anti-Aging Treatment',
                'description' => 'Advanced anti-aging facial treatment',
                'price' => 150.00,
                'duration' => 120,
                'type' => 'beauty',
                'is_active' => true,
            ],

            // Health Services
            [
                'specialist_id' => $specialists->where('type', 'health')->first()->id,
                'name' => 'Nutrition Consultation',
                'description' => 'Personalized nutrition and diet consultation',
                'price' => 100.00,
                'duration' => 60,
                'type' => 'health',
                'is_active' => true,
            ],
            [
                'specialist_id' => $specialists->where('type', 'health')->first()->id,
                'name' => 'Wellness Assessment',
                'description' => 'Comprehensive health and wellness assessment',
                'price' => 150.00,
                'duration' => 90,
                'type' => 'health',
                'is_active' => true,
            ],
            [
                'specialist_id' => $specialists->where('type', 'health')->skip(1)->first()->id,
                'name' => 'Mental Health Counseling',
                'description' => 'One-on-one mental health counseling session',
                'price' => 120.00,
                'duration' => 60,
                'type' => 'health',
                'is_active' => true,
            ],

            // Fitness Services
            [
                'specialist_id' => $specialists->where('type', 'fitness')->first()->id,
                'name' => 'Personal Training',
                'description' => 'One-on-one personal training session',
                'price' => 80.00,
                'duration' => 60,
                'type' => 'fitness',
                'is_active' => true,
            ],
            [
                'specialist_id' => $specialists->where('type', 'fitness')->first()->id,
                'name' => 'Strength Training',
                'description' => 'Focused strength training workout',
                'price' => 70.00,
                'duration' => 45,
                'type' => 'fitness',
                'is_active' => true,
            ],
            [
                'specialist_id' => $specialists->where('type', 'fitness')->skip(1)->first()->id,
                'name' => 'Yoga Session',
                'description' => 'Relaxing yoga and meditation session',
                'price' => 60.00,
                'duration' => 60,
                'type' => 'fitness',
                'is_active' => true,
            ],
            [
                'specialist_id' => $specialists->where('type', 'fitness')->skip(1)->first()->id,
                'name' => 'Meditation Class',
                'description' => 'Guided meditation and mindfulness class',
                'price' => 40.00,
                'duration' => 30,
                'type' => 'fitness',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
