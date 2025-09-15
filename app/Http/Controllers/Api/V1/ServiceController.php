<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends BaseApiController
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $services = $this->serviceService->getAllServices($perPage);
        return $this->respondWithSuccess('Services retrieved successfully', $services);
    }

    public function show(int $serviceId)
    {
        $result = $this->serviceService->getService($serviceId);

        if (isset($result['error'])) {
            return $this->respondWithError($result['error'], 404);
        }

        return $this->respondWithSuccess('Service retrieved successfully', $result);
    }

    public function store(Request $request)
    {
        $specialist = $request->user();
        $result = $this->serviceService->createService($request->all(), $specialist->id);

        if (isset($result['error'])) {
            if (isset($result['errors'])) {
                return $this->respondWithError($result['error'], 422, $result['errors']);
            }
            return $this->respondWithError($result['error'], 422);
        }

        return $this->setStatusCode(201)->respondWithSuccess('Service created successfully', $result);
    }

    public function update(Request $request, int $serviceId)
    {
        $specialist = $request->user();
        $result = $this->serviceService->updateService($serviceId, $request->all(), $specialist->id);

        if (isset($result['error'])) {
            if (isset($result['errors'])) {
                return $this->respondWithError($result['error'], 422, $result['errors']);
            }
            $statusCode = $result['error'] === 'Service not found' ? 404 : 403;
            return $this->respondWithError($result['error'], $statusCode);
        }

        return $this->respondWithSuccess('Service updated successfully', $result);
    }

    public function destroy(Request $request, int $serviceId)
    {
        $specialist = $request->user();
        $result = $this->serviceService->deleteService($serviceId, $specialist->id);

        if (isset($result['error'])) {
            $statusCode = $result['error'] === 'Service not found' ? 404 : 403;
            return $this->respondWithError($result['error'], $statusCode);
        }

        return $this->respondWithSuccess('Service deleted successfully');
    }

    public function specialistServices(Request $request)
    {
        $specialist = $request->user();
        $perPage = $request->get('per_page', 15);
        $services = $this->serviceService->getSpecialistServices($specialist->id, $perPage);
        return $this->respondWithSuccess('Services retrieved successfully', $services);
    }



    public function stats(Request $request)
    {
        $specialist = $request->user();
        $stats = $this->serviceService->getServiceStats($specialist->id);
        return $this->respondWithSuccess('Statistics retrieved successfully', $stats);
    }
}
