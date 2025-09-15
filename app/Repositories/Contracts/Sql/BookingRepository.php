<?php

namespace App\Repositories\Contracts\Sql;

use App\Models\Booking;
use App\Repositories\Contracts\BookingContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BookingRepository implements BookingContract
{
    public function __construct(Booking $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['user', 'specialist', 'service'])->get();
    }

    public function create(array $attributes = []): mixed
    {
        return $this->model->create($attributes);
    }

    public function update(Model $model, array $attributes = []): mixed
    {
        $model->update($attributes);
        return $model;
    }

    public function remove(Model $model): mixed
    {
        return $this->model->destroy($model->id);
    }

    public function find(int $id, array $relations = [], array $filters = []): mixed
    {
        return $this->model->with($relations)->find($id);
    }

    public function findOrFail(int $id, array $relations = [], array $filters = []): mixed
    {
        return $this->model->with($relations)->findOrFail($id);
    }

    public function getUserBookings(int $userId, int $perPage = 15)
    {
        return $this->model
            ->with(['specialist', 'service'])
            ->where('user_id', $userId)
            ->orderBy('start_time', 'desc')
            ->paginate($perPage);
    }

    public function getSpecialistBookings(int $specialistId, int $perPage = 15)
    {
        return $this->model
            ->with(['user', 'service'])
            ->where('specialist_id', $specialistId)
            ->orderBy('start_time', 'desc')
            ->paginate($perPage);
    }

    public function isSpecialistAvailable(int $specialistId, string $startTime, string $endTime, ?int $excludeBookingId = null): bool
    {
        $startTime = Carbon::parse($startTime);
        $endTime = Carbon::parse($endTime);

        $query = $this->model
            ->where('specialist_id', $specialistId)
            ->where('status', 'confirmed')
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($q2) use ($startTime, $endTime) {
                    $q2->where('start_time', '<=', $startTime)
                       ->where('end_time', '>', $startTime);
                })->orWhere(function ($q3) use ($startTime, $endTime) {
                    $q3->where('start_time', '<', $endTime)
                       ->where('end_time', '>=', $endTime);
                })->orWhere(function ($q4) use ($startTime, $endTime) {
                    $q4->where('start_time', '>=', $startTime)
                       ->where('end_time', '<=', $endTime);
                })->orWhere(function ($q5) use ($startTime, $endTime) {
                    $q5->where('start_time', '<=', $startTime)
                       ->where('end_time', '>=', $endTime);
                });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->count() === 0;
    }

    public function getConflictingBookings(int $specialistId, string $startTime, string $endTime, ?int $excludeBookingId = null): Collection
    {
        $startTime = Carbon::parse($startTime);
        $endTime = Carbon::parse($endTime);

        $query = $this->model
            ->with(['user', 'service'])
            ->where('specialist_id', $specialistId)
            ->where('status', 'confirmed')
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($q2) use ($startTime, $endTime) {
                    $q2->where('start_time', '<=', $startTime)
                       ->where('end_time', '>', $startTime);
                })->orWhere(function ($q3) use ($startTime, $endTime) {
                    $q3->where('start_time', '<', $endTime)
                       ->where('end_time', '>=', $endTime);
                })->orWhere(function ($q4) use ($startTime, $endTime) {
                    $q4->where('start_time', '>=', $startTime)
                       ->where('end_time', '<=', $endTime);
                })->orWhere(function ($q5) use ($startTime, $endTime) {
                    $q5->where('start_time', '<=', $startTime)
                       ->where('end_time', '>=', $endTime);
                });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->get();
    }

    public function cancelBooking(int $id): bool
    {
        return $this->model->where('id', $id)->update(['status' => 'cancelled']);
    }

    public function getUserBookingStats(int $userId): array
    {
        return [
            'total_bookings' => $this->model->where('user_id', $userId)->count(),
            'confirmed_bookings' => $this->model->where('user_id', $userId)->confirmed()->count(),
            'cancelled_bookings' => $this->model->where('user_id', $userId)->cancelled()->count(),
            'upcoming_bookings' => $this->model->where('user_id', $userId)->upcoming()->confirmed()->count(),
            'past_bookings' => $this->model->where('user_id', $userId)->past()->count(),
        ];
    }

    public function getSpecialistBookingStats(int $specialistId): array
    {
        return [
            'total_bookings' => $this->model->where('specialist_id', $specialistId)->count(),
            'confirmed_bookings' => $this->model->where('specialist_id', $specialistId)->confirmed()->count(),
            'cancelled_bookings' => $this->model->where('specialist_id', $specialistId)->cancelled()->count(),
            'upcoming_bookings' => $this->model->where('specialist_id', $specialistId)->upcoming()->confirmed()->count(),
            'past_bookings' => $this->model->where('specialist_id', $specialistId)->past()->count(),
        ];
    }
}
