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
            [
                'specialist_id' => $specialists->where('type', 'beauty')->first()->id,
                'title' => 'Haircut & Styling',
                'price' => 50.00,
                'duration' => 60,
            ],
            [
                'specialist_id' => $specialists->where('type', 'beauty')->first()->id,
                'title' => 'Hair Coloring',
                'price' => 120.00,
                'duration' => 120,
            ],
            [
                'specialist_id' => $specialists->where('type', 'beauty')->skip(1)->first()->id,
                'title' => 'Facial Treatment',
                'price' => 80.00,
                'duration' => 90,
            ],
            [
                'specialist_id' => $specialists->where('type', 'beauty')->skip(1)->first()->id,
                'title' => 'Anti-Aging Treatment',
                'price' => 150.00,
                'duration' => 120,
            ],

            [
                'specialist_id' => $specialists->where('type', 'health')->first()->id,
                'title' => 'Nutrition Consultation',
                'price' => 100.00,
                'duration' => 60,
            ],
            [
                'specialist_id' => $specialists->where('type', 'health')->first()->id,
                'title' => 'Wellness Assessment',
                'price' => 150.00,
                'duration' => 90,
            ],
            [
                'specialist_id' => $specialists->where('type', 'health')->skip(1)->first()->id,
                'title' => 'Mental Health Counseling',
                'price' => 120.00,
                'duration' => 60,
            ],

            [
                'specialist_id' => $specialists->where('type', 'fitness')->first()->id,
                'title' => 'Personal Training',
                'price' => 80.00,
                'duration' => 60,
            ],
            [
                'specialist_id' => $specialists->where('type', 'fitness')->first()->id,
                'title' => 'Strength Training',
                'price' => 70.00,
                'duration' => 45,
            ],
            [
                'specialist_id' => $specialists->where('type', 'fitness')->skip(1)->first()->id,
                'title' => 'Yoga Session',
                'price' => 60.00,
                'duration' => 60,
            ],
            [
                'specialist_id' => $specialists->where('type', 'fitness')->skip(1)->first()->id,
                'title' => 'Meditation Class',
                'price' => 40.00,
                'duration' => 30,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
