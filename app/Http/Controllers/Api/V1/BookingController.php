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
        $result = $this->bookingService->getUserBookings($user->id);

        if ($result->getStatusCode() === 200) {
            $data = $result->getData(true);
            return $this->respondWithSuccess('Bookings retrieved successfully', $data);
        }

        return $result;
    }

    public function specialistBookings(Request $request)
    {
        $specialist = $request->user();
        $result = $this->bookingService->getSpecialistBookings($specialist->id);

        if ($result->getStatusCode() === 200) {
            $data = $result->getData(true);
            return $this->respondWithSuccess('Specialist bookings retrieved successfully', $data);
        }

        return $result;
    }

    public function store(StoreBookingRequest $request)
    {
        $user = $request->user();
        $result = $this->bookingService->createBooking($request->validated(), $user->id);

        if ($result->getStatusCode() === 201) {
            $data = $result->getData(true);
            return $this->setStatusCode(201)->respondWithSuccess('Booking created successfully', $data);
        }

        return $result;
    }

    public function show(Request $request, int $bookingId)
    {
        $user = $request->user();
        $result = $this->bookingService->getBooking($bookingId, $user->id);

        if ($result->getStatusCode() === 200) {
            $data = $result->getData(true);
            return $this->respondWithSuccess('Booking retrieved successfully', $data);
        }

        return $result;
    }

    public function update(UpdateBookingRequest $request, int $bookingId)
    {
        $user = $request->user();
        $result = $this->bookingService->updateBooking($bookingId, $request->validated(), $user->id);

        if ($result->getStatusCode() === 200) {
            $data = $result->getData(true);
            return $this->respondWithSuccess('Booking updated successfully', $data);
        }

        return $result;
    }

    public function cancel(Request $request, int $bookingId)
    {
        $user = $request->user();
        $result = $this->bookingService->cancelBooking($bookingId, $user->id);

        if ($result->getStatusCode() === 200) {
            $data = $result->getData(true);
            return $this->respondWithSuccess('Booking cancelled successfully', $data);
        }

        return $result;
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

        if ($result->getStatusCode() === 200) {
            $data = $result->getData(true);
            return $this->respondWithSuccess('Available slots retrieved successfully', $data);
        }

        return $result;
    }

}
