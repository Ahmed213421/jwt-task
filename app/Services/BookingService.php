<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Specialist;
use App\Repositories\Contracts\BookingContract;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class BookingService
{
    protected $bookingRepository;

    public function __construct(BookingContract $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function createBooking(array $data, int $userId): JsonResponse
    {
        $validation = $this->validateBookingData($data);
        if (!$validation['valid']) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validation['errors']
            ], 422);
        }

        if (!$this->checkSpecialistAvailability($data['specialist_id'], $data['start_time'], $data['end_time'])) {
            $conflictingBookings = $this->bookingRepository->getConflictingBookings(
                $data['specialist_id'],
                $data['start_time'],
                $data['end_time']
            );

            return response()->json([
                'message' => 'The specialist is not available at the requested time',
                'conflicting_bookings' => $conflictingBookings->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'start_time' => $booking->start_time->format('Y-m-d H:i:s'),
                        'end_time' => $booking->end_time->format('Y-m-d H:i:s'),
                        'service' => $booking->service->name,
                        'user' => $booking->user->name
                    ];
                })
            ], 422);
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

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking
        ], 201);
    }

    public function updateBooking(int $bookingId, array $data, int $userId): JsonResponse
    {
        $booking = $this->bookingRepository->find($bookingId, ['user', 'specialist', 'service']);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($booking->user_id !== $userId && $booking->specialist_id !== $userId) {
            return response()->json(['message' => 'Unauthorized access to booking'], 403);
        }

        if ($booking->start_time < now()) {
            return response()->json(['message' => 'Cannot update past bookings'], 422);
        }

        if ($booking->status === 'cancelled') {
            return response()->json(['message' => 'Cannot update cancelled bookings'], 422);
        }

        $validation = $this->validateBookingData($data);
        if (!$validation['valid']) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validation['errors']
            ], 422);
        }

        if (!$this->checkSpecialistAvailability($data['specialist_id'], $data['start_time'], $data['end_time'], $bookingId)) {
            $conflictingBookings = $this->bookingRepository->getConflictingBookings(
                $data['specialist_id'],
                $data['start_time'],
                $data['end_time'],
                $bookingId
            );

            return response()->json([
                'message' => 'The specialist is not available at the requested time',
                'conflicting_bookings' => $conflictingBookings->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'start_time' => $booking->start_time->format('Y-m-d H:i:s'),
                        'end_time' => $booking->end_time->format('Y-m-d H:i:s'),
                        'service' => $booking->service->name,
                        'user' => $booking->user->name
                    ];
                })
            ], 422);
        }

        $this->bookingRepository->update($booking, $data);
        $booking->refresh();
        $booking->load(['user', 'specialist', 'service']);

        return response()->json([
            'message' => 'Booking updated successfully',
            'booking' => $booking
        ]);
    }

    public function cancelBooking(int $bookingId, int $userId): JsonResponse
    {
        $booking = $this->bookingRepository->find($bookingId, ['user', 'specialist', 'service']);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($booking->user_id !== $userId && $booking->specialist_id !== $userId) {
            return response()->json(['message' => 'Unauthorized access to booking'], 403);
        }

        if ($booking->status === 'cancelled') {
            return response()->json(['message' => 'Booking is already cancelled'], 422);
        }

        if ($booking->start_time < now()) {
            return response()->json(['message' => 'Cannot cancel past bookings'], 422);
        }

        $this->bookingRepository->cancelBooking($bookingId);
        $booking->refresh();
        $booking->load(['user', 'specialist', 'service']);

        return response()->json([
            'message' => 'Booking cancelled successfully',
            'booking' => $booking
        ]);
    }

    public function getUserBookings(int $userId, int $perPage = 15): JsonResponse
    {
        $bookings = $this->bookingRepository->getUserBookings($userId, $perPage);

        return response()->json([
            'message' => 'Bookings retrieved successfully',
            'bookings' => $bookings
        ]);
    }

    public function getSpecialistBookings(int $specialistId, int $perPage = 15): JsonResponse
    {
        $bookings = $this->bookingRepository->getSpecialistBookings($specialistId, $perPage);

        return response()->json([
            'message' => 'Specialist bookings retrieved successfully',
            'bookings' => $bookings
        ]);
    }

    public function getBooking(int $bookingId, int $userId): JsonResponse
    {
        $booking = $this->bookingRepository->find($bookingId, ['user', 'specialist', 'service']);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($booking->user_id !== $userId && $booking->specialist_id !== $userId) {
            return response()->json(['message' => 'Unauthorized access to booking'], 403);
        }

        return response()->json([
            'message' => 'Booking retrieved successfully',
            'booking' => $booking
        ]);
    }

    public function getAvailableSlots(int $specialistId, string $date, int $serviceId): JsonResponse
    {
        $specialist = Specialist::find($specialistId);
        if (!$specialist || !$specialist->is_active) {
            return response()->json(['message' => 'Specialist not found or inactive'], 404);
        }

        $service = Service::where('id', $serviceId)
            ->where('specialist_id', $specialistId)
            ->first();

        if (!$service) {
            return response()->json(['message' => 'Service not found for this specialist'], 404);
        }

        $availableSlots = $this->bookingRepository->getAvailableTimeSlots($specialistId, $date);

        return response()->json([
            'message' => 'Available slots retrieved successfully',
            'specialist' => $specialist->name,
            'service' => $service->name,
            'date' => Carbon::parse($date)->format('Y-m-d'),
            'available_slots' => $availableSlots
        ]);
    }

    public function getUserStats(int $userId): JsonResponse
    {
        $stats = $this->bookingRepository->getUserBookingStats($userId);

        return response()->json([
            'message' => 'Statistics retrieved successfully',
            'stats' => $stats
        ]);
    }

    public function getSpecialistStats(int $specialistId): JsonResponse
    {
        $stats = $this->bookingRepository->getSpecialistBookingStats($specialistId);

        return response()->json([
            'message' => 'Statistics retrieved successfully',
            'stats' => $stats
        ]);
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

        // Validate specialist exists and is active
        $specialist = Specialist::find($data['specialist_id']);
        if (!$specialist || !$specialist->is_active) {
            return [
                'valid' => false,
                'errors' => ['specialist_id' => ['The selected specialist is not available or inactive.']]
            ];
        }

        // Validate service exists and belongs to the specialist
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

        // Enhanced time validation
        if ($startTime->lt($now)) {
            return [
                'valid' => false,
                'errors' => ['start_time' => ['Booking start time must be in the future.']]
            ];
        }

        // Check if booking is too far in the future (max 6 months)
        if ($startTime->gt($now->copy()->addMonths(6))) {
            return [
                'valid' => false,
                'errors' => ['start_time' => ['Booking cannot be made more than 6 months in advance.']]
            ];
        }

        $duration = $endTime->diffInHours($startTime);

        // Validate duration limits
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

        // Check if booking is during business hours (9 AM to 11 PM)
        $startHour = $startTime->hour;
        $endHour = $endTime->hour;

        if ($startHour < 9 || $endHour > 23) {
            return [
                'valid' => false,
                'errors' => ['start_time' => ['Bookings can only be made between 9:00 AM and 11:00 PM.']]
            ];
        }

        // Check if booking is on a valid day (Monday to Sunday)
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
