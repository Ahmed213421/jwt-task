<?php

namespace App\Repositories\Contracts;

interface BookingContract extends BaseContract
{
    public function getUserBookings(int $userId, int $perPage = 15);
    public function getSpecialistBookings(int $specialistId, int $perPage = 15);
    public function isSpecialistAvailable(int $specialistId, string $startTime, string $endTime, ?int $excludeBookingId = null): bool;
    public function getConflictingBookings(int $specialistId, string $startTime, string $endTime, ?int $excludeBookingId = null): \Illuminate\Support\Collection;
    public function getAvailableTimeSlots(int $specialistId, string $date): array;
    public function cancelBooking(int $id): bool;
    public function getUserBookingStats(int $userId): array;
    public function getSpecialistBookingStats(int $specialistId): array;
}
