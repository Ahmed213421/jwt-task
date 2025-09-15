<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Specialist;
use App\Repositories\Contracts\BookingContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class BookingService
{
    protected $bookingRepository;

    public function __construct(BookingContract $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function createBooking(array $data, int $userId): array
    {
        $validation = $this->validateBookingData($data);
        if (!$validation['valid']) {
            return [
                'error' => 'Validation failed',
                'errors' => $validation['errors']
            ];
        }

        if (!$this->checkSpecialistAvailability($data['specialist_id'], $data['start_time'], $data['end_time'])) {
            $conflictingBookings = $this->bookingRepository->getConflictingBookings(
                $data['specialist_id'],
                $data['start_time'],
                $data['end_time']
            );

            return [
                'error' => 'The specialist is not available at the requested time',
                'conflicting_bookings' => $conflictingBookings->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'start_time' => $booking->start_time->format('Y-m-d H:i:s'),
                        'end_time' => $booking->end_time->format('Y-m-d H:i:s'),
                        'service' => $booking->service->title,
                        'user' => $booking->user->name
                    ];
                })
            ];
        }

        $booking = $this->bookingRepository->create([
            'user_id' => $userId,
            'specialist_id' => $data['specialist_id'],
            'service_id' => $data['service_id'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => 'confirmed'
        ]);

        $booking->load(['specialist', 'service']);

        return $booking;
    }

    public function updateBooking(int $bookingId, array $data, int $userId): array
    {
        $booking = $this->bookingRepository->find($bookingId, ['user', 'specialist', 'service']);

        if (!$booking) {
            return ['error' => 'Booking not found'];
        }

        if ($booking->user_id !== $userId && $booking->specialist_id !== $userId) {
            return ['error' => 'Unauthorized access to booking'];
        }

        if ($booking->start_time < now()) {
            return ['error' => 'Cannot update past bookings'];
        }

        if ($booking->status === 'cancelled') {
            return ['error' => 'Cannot update cancelled bookings'];
        }

        $validation = $this->validateBookingData($data);
        if (!$validation['valid']) {
            return [
                'error' => 'Validation failed',
                'errors' => $validation['errors']
            ];
        }

        if (!$this->checkSpecialistAvailability($data['specialist_id'], $data['start_time'], $data['end_time'], $bookingId)) {
            $conflictingBookings = $this->bookingRepository->getConflictingBookings(
                $data['specialist_id'],
                $data['start_time'],
                $data['end_time'],
                $bookingId
            );

            return [
                'error' => 'The specialist is not available at the requested time',
                'conflicting_bookings' => $conflictingBookings->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'start_time' => $booking->start_time->format('Y-m-d H:i:s'),
                        'end_time' => $booking->end_time->format('Y-m-d H:i:s'),
                        'service' => $booking->service->title,
                        'user' => $booking->user->name
                    ];
                })
            ];
        }

        $this->bookingRepository->update($booking, $data);
        $booking->refresh();
        $booking->load(['user', 'specialist', 'service']);

        return $booking->toArray();
    }

    public function cancelBooking(int $bookingId, int $userId): array
    {
        $booking = $this->bookingRepository->find($bookingId, ['user', 'specialist', 'service']);

        if (!$booking) {
            return ['error' => 'Booking not found'];
        }

        if ($booking->user_id !== $userId && $booking->specialist_id !== $userId) {
            return ['error' => 'Unauthorized access to booking'];
        }

        if ($booking->status === 'cancelled') {
            return ['error' => 'Booking is already cancelled'];
        }

        if ($booking->start_time < now()) {
            return ['error' => 'Cannot cancel past bookings'];
        }

        $this->bookingRepository->cancelBooking($bookingId);
        $booking->refresh();
        $booking->load(['user', 'specialist', 'service']);

        return $booking->toArray();
    }

    public function getUserBookings(int $userId, int $perPage = 15): array
    {
        return $this->bookingRepository->getUserBookings($userId, $perPage);
    }

    public function getSpecialistBookings(int $specialistId, int $perPage = 15): array
    {
        return $this->bookingRepository->getSpecialistBookings($specialistId, $perPage);
    }

    public function getBooking(int $bookingId, int $userId): array
    {
        $booking = $this->bookingRepository->find($bookingId, ['user', 'specialist', 'service']);

        if (!$booking) {
            return ['error' => 'Booking not found'];
        }

        if ($booking->user_id !== $userId && $booking->specialist_id !== $userId) {
            return ['error' => 'Unauthorized access to booking'];
        }

        return $booking->toArray();
    }

    public function getAvailableSlots(int $specialistId, string $date, int $serviceId): array
    {
        $specialist = Specialist::find($specialistId);
        if (!$specialist || !$specialist->is_active) {
            return ['error' => 'Specialist not found or inactive'];
        }

        $service = Service::where('id', $serviceId)
            ->where('specialist_id', $specialistId)
            ->first();

        if (!$service) {
            return ['error' => 'Service not found for this specialist'];
        }

        $availableSlots = $this->bookingRepository->getAvailableTimeSlots($specialistId, $date);

        return [
            'specialist' => $specialist->name,
            'service' => $service->title,
            'date' => Carbon::parse($date)->format('Y-m-d'),
            'available_slots' => $availableSlots
        ];
    }

    public function getUserStats(int $userId): array
    {
        return $this->bookingRepository->getUserBookingStats($userId);
    }

    public function getSpecialistStats(int $specialistId): array
    {
        return $this->bookingRepository->getSpecialistBookingStats($specialistId);
    }

    public function validateBookingData(array $data): array
    {
        $validator = Validator::make($data, [
            'specialist_id' => 'required|exists:specialists,id',
            'service_id' => 'required|exists:services,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        if ($validator->fails()) {
            return [
                'valid' => false,
                'errors' => $validator->errors()
            ];
        }

        $specialist = Specialist::find($data['specialist_id']);
        if (!$specialist || !$specialist->is_active) {
            return [
                'valid' => false,
                'errors' => ['specialist_id' => ['The selected specialist is not available or inactive.']]
            ];
        }

        $service = Service::where('id', $data['service_id'])
            ->where('specialist_id', $data['specialist_id'])
            ->first();

        if (!$service) {
            return [
                'valid' => false,
                'errors' => ['service_id' => ['The selected service does not belong to this specialist.']]
            ];
        }

        $startTime = Carbon::parse($data['start_time']);
        $endTime = Carbon::parse($data['end_time']);
        $now = Carbon::now();

        if ($startTime->lt($now)) {
            return [
                'valid' => false,
                'errors' => ['start_time' => ['Booking start time must be in the future.']]
            ];
        }

        if ($startTime->gt($now->copy()->addMonths(6))) {
            return [
                'valid' => false,
                'errors' => ['start_time' => ['Booking cannot be made more than 6 months in advance.']]
            ];
        }

        $duration = $endTime->diffInHours($startTime);

        if ($duration > 8) {
            return [
                'valid' => false,
                'errors' => ['end_time' => ['Booking duration cannot exceed 8 hours.']]
            ];
        }

        if ($duration < 0.5) {
            return [
                'valid' => false,
                'errors' => ['end_time' => ['Booking duration must be at least 30 minutes.']]
            ];
        }

        $startHour = $startTime->hour;
        $endHour = $endTime->hour;

        if ($startHour < 9 || $endHour > 23) {
            return [
                'valid' => false,
                'errors' => ['start_time' => ['Bookings can only be made between 9:00 AM and 11:00 PM.']]
            ];
        }

        $dayOfWeek = $startTime->dayOfWeek;
        if ($dayOfWeek < 1 || $dayOfWeek > 7) {
            return [
                'valid' => false,
                'errors' => ['start_time' => ['Invalid booking day.']]
            ];
        }

        return ['valid' => true, 'errors' => []];
    }

    public function checkSpecialistAvailability(int $specialistId, string $startTime, string $endTime, ?int $excludeBookingId = null): bool
    {
        return $this->bookingRepository->isSpecialistAvailable($specialistId, $startTime, $endTime, $excludeBookingId);
    }
}
