<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends BaseApiController
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $bookings = $this->bookingService->getUserBookings($user->id);
        return $this->respondWithSuccess('Bookings retrieved successfully', $bookings);
    }

    public function specialistBookings(Request $request)
    {
        $specialist = $request->user();
        $bookings = $this->bookingService->getSpecialistBookings($specialist->id);
        return $this->respondWithSuccess('Specialist bookings retrieved successfully', $bookings);
    }

    public function store(StoreBookingRequest $request)
    {
        $user = $request->user();
        $result = $this->bookingService->createBooking($request->validated(), $user->id);

        if (isset($result['error'])) {
            if (isset($result['errors'])) {
                return $this->respondWithError($result['error'], 422, $result['errors']);
            }
            if (isset($result['conflicting_bookings'])) {
                return $this->respondWithError($result['error'], 422, ['conflicting_bookings' => $result['conflicting_bookings']]);
            }
            return $this->respondWithError($result['error'], 422);
        }

        return $this->setStatusCode(201)->respondWithSuccess('Booking created successfully', $result);
    }

    public function show(Request $request, int $bookingId)
    {
        $user = $request->user();
        $result = $this->bookingService->getBooking($bookingId, $user->id);

        if (isset($result['error'])) {
            $statusCode = $result['error'] === 'Booking not found' ? 404 : 403;
            return $this->respondWithError($result['error'], $statusCode);
        }

        return $this->respondWithSuccess('Booking retrieved successfully', $result);
    }

    public function update(UpdateBookingRequest $request, int $bookingId)
    {
        $user = $request->user();
        $result = $this->bookingService->updateBooking($bookingId, $request->validated(), $user->id);

        if (isset($result['error'])) {
            if (isset($result['errors'])) {
                return $this->respondWithError($result['error'], 422, $result['errors']);
            }
            if (isset($result['conflicting_bookings'])) {
                return $this->respondWithError($result['error'], 422, ['conflicting_bookings' => $result['conflicting_bookings']]);
            }
            $statusCode = in_array($result['error'], ['Booking not found', 'Unauthorized access to booking']) ? 404 : 422;
            return $this->respondWithError($result['error'], $statusCode);
        }

        return $this->respondWithSuccess('Booking updated successfully', $result);
    }

    public function cancel(Request $request, int $bookingId)
    {
        $user = $request->user();
        $result = $this->bookingService->cancelBooking($bookingId, $user->id);

        if (isset($result['error'])) {
            $statusCode = $result['error'] === 'Booking not found' ? 404 : 422;
            return $this->respondWithError($result['error'], $statusCode);
        }

        return $this->respondWithSuccess('Booking cancelled successfully', $result);
    }

    public function availableSlots(Request $request, int $specialistId)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:services,id'
        ]);

        $result = $this->bookingService->getAvailableSlots(
            $specialistId,
            $request->date,
            $request->service_id
        );

        if (isset($result['error'])) {
            return $this->respondWithError($result['error'], 404);
        }

        return $this->respondWithSuccess('Available slots retrieved successfully', $result);
    }

    public function specialistStats(Request $request)
    {
        $specialist = $request->user();
        $stats = $this->bookingService->getSpecialistStats($specialist->id);
        return $this->respondWithSuccess('Statistics retrieved successfully', $stats);
    }

    public function userStats(Request $request)
    {
        $user = $request->user();
        $stats = $this->bookingService->getUserStats($user->id);
        return $this->respondWithSuccess('Statistics retrieved successfully', $stats);
    }
}
