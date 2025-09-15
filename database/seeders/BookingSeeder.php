<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use App\Models\Specialist;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $specialists = Specialist::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();

        $bookings = [
            // Past bookings
            [
                'user_id' => $users->first()->id,
                'specialist_id' => $specialists->first()->id,
                'service_id' => $services->where('specialist_id', $specialists->first()->id)->first()->id,
                'start_time' => Carbon::now()->subDays(7)->setTime(10, 0),
                'end_time' => Carbon::now()->subDays(7)->setTime(11, 0),
                'status' => 'confirmed',
            ],
            [
                'user_id' => $users->skip(1)->first()->id,
                'specialist_id' => $specialists->first()->id,
                'service_id' => $services->where('specialist_id', $specialists->first()->id)->first()->id,
                'start_time' => Carbon::now()->subDays(5)->setTime(14, 0),
                'end_time' => Carbon::now()->subDays(5)->setTime(15, 0),
                'status' => 'confirmed',
            ],
            [
                'user_id' => $users->skip(2)->first()->id,
                'specialist_id' => $specialists->skip(1)->first()->id,
                'service_id' => $services->where('specialist_id', $specialists->skip(1)->first()->id)->first()->id,
                'start_time' => Carbon::now()->subDays(3)->setTime(9, 0),
                'end_time' => Carbon::now()->subDays(3)->setTime(10, 30),
                'status' => 'cancelled',
            ],

            // Today's bookings
            [
                'user_id' => $users->skip(3)->first()->id,
                'specialist_id' => $specialists->first()->id,
                'service_id' => $services->where('specialist_id', $specialists->first()->id)->first()->id,
                'start_time' => Carbon::today()->setTime(10, 0),
                'end_time' => Carbon::today()->setTime(11, 0),
                'status' => 'confirmed',
            ],
            [
                'user_id' => $users->skip(4)->first()->id,
                'specialist_id' => $specialists->skip(1)->first()->id,
                'service_id' => $services->where('specialist_id', $specialists->skip(1)->first()->id)->first()->id,
                'start_time' => Carbon::today()->setTime(14, 0),
                'end_time' => Carbon::today()->setTime(15, 30),
                'status' => 'confirmed',
            ],

            // Future bookings
            [
                'user_id' => $users->first()->id,
                'specialist_id' => $specialists->skip(2)->first()->id,
                'service_id' => $services->where('specialist_id', $specialists->skip(2)->first()->id)->first()->id,
                'start_time' => Carbon::tomorrow()->setTime(9, 0),
                'end_time' => Carbon::tomorrow()->setTime(10, 0),
                'status' => 'confirmed',
            ],
            [
                'user_id' => $users->skip(1)->first()->id,
                'specialist_id' => $specialists->first()->id,
                'service_id' => $services->where('specialist_id', $specialists->first()->id)->first()->id,
                'start_time' => Carbon::now()->addDays(2)->setTime(11, 0),
                'end_time' => Carbon::now()->addDays(2)->setTime(12, 0),
                'status' => 'confirmed',
            ],
            [
                'user_id' => $users->skip(2)->first()->id,
                'specialist_id' => $specialists->skip(1)->first()->id,
                'service_id' => $services->where('specialist_id', $specialists->skip(1)->first()->id)->first()->id,
                'start_time' => Carbon::now()->addDays(3)->setTime(15, 0),
                'end_time' => Carbon::now()->addDays(3)->setTime(16, 30),
                'status' => 'confirmed',
            ],
            [
                'user_id' => $users->skip(3)->first()->id,
                'specialist_id' => $specialists->skip(2)->first()->id,
                'service_id' => $services->where('specialist_id', $specialists->skip(2)->first()->id)->first()->id,
                'start_time' => Carbon::now()->addDays(5)->setTime(10, 0),
                'end_time' => Carbon::now()->addDays(5)->setTime(11, 0),
                'status' => 'confirmed',
            ],
            [
                'user_id' => $users->skip(4)->first()->id,
                'specialist_id' => $specialists->first()->id,
                'service_id' => $services->where('specialist_id', $specialists->first()->id)->first()->id,
                'start_time' => Carbon::now()->addDays(7)->setTime(16, 0),
                'end_time' => Carbon::now()->addDays(7)->setTime(17, 0),
                'status' => 'confirmed',
            ],

            // Conflicting bookings (for testing conflict detection)
            [
                'user_id' => $users->first()->id,
                'specialist_id' => $specialists->first()->id,
                'service_id' => $services->where('specialist_id', $specialists->first()->id)->first()->id,
                'start_time' => Carbon::now()->addDays(10)->setTime(10, 0),
                'end_time' => Carbon::now()->addDays(10)->setTime(11, 0),
                'status' => 'confirmed',
            ],
            [
                'user_id' => $users->skip(1)->first()->id,
                'specialist_id' => $specialists->first()->id,
                'service_id' => $services->where('specialist_id', $specialists->first()->id)->first()->id,
                'start_time' => Carbon::now()->addDays(10)->setTime(10, 30),
                'end_time' => Carbon::now()->addDays(10)->setTime(11, 30),
                'status' => 'confirmed',
            ],
        ];

        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}
