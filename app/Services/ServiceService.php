<?php

namespace App\Services;

use App\Models\Service;
use App\Repositories\Contracts\ServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ServiceService
{
    protected $serviceRepository;

    public function __construct(ServiceContract $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function createService(array $data, int $specialistId): JsonResponse
    {
        $validation = $this->validateServiceData($data);
        if (!$validation['valid']) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validation['errors']
            ], 422);
        }

        $service = $this->serviceRepository->create([
            'specialist_id' => $specialistId,
            'title' => $data['title'],
            'price' => $data['price'],
            'duration' => $data['duration']
        ]);

        $service->load(['specialist']);

        return response()->json([
            'message' => 'Service created successfully',
            'service' => $service
        ], 201);
    }

    public function updateService(int $serviceId, array $data, int $specialistId): JsonResponse
    {
        $service = $this->serviceRepository->find($serviceId, ['specialist']);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        if ($service->specialist_id !== $specialistId) {
            return response()->json(['message' => 'Unauthorized access to service'], 403);
        }

        $validation = $this->validateServiceData($data);
        if (!$validation['valid']) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validation['errors']
            ], 422);
        }

        $this->serviceRepository->update($service, $data);
        $service->refresh();
        $service->load(['specialist']);

        return response()->json([
            'message' => 'Service updated successfully',
            'service' => $service
        ]);
    }

    public function deleteService(int $serviceId, int $specialistId): JsonResponse
    {
        $service = $this->serviceRepository->find($serviceId, ['specialist']);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        if ($service->specialist_id !== $specialistId) {
            return response()->json(['message' => 'Unauthorized access to service'], 403);
        }

        $this->serviceRepository->remove($service);

        return response()->json([
            'message' => 'Service deleted successfully'
        ]);
    }

    public function getSpecialistServices(int $specialistId, int $perPage = 15): JsonResponse
    {
        $services = $this->serviceRepository->getServicesBySpecialist($specialistId, $perPage);

        return response()->json([
            'message' => 'Services retrieved successfully',
            'services' => $services
        ]);
    }

    public function getAllServices(int $perPage = 15): JsonResponse
    {
        $services = $this->serviceRepository->getActiveServices($perPage);

        return response()->json([
            'message' => 'Services retrieved successfully',
            'services' => $services
        ]);
    }

    public function getService(int $serviceId): JsonResponse
    {
        $service = $this->serviceRepository->find($serviceId, ['specialist']);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        return response()->json([
            'message' => 'Service retrieved successfully',
            'service' => $service
        ]);
    }

    public function searchServices(string $query, int $perPage = 15): JsonResponse
    {
        $services = $this->serviceRepository->searchServices($query, $perPage);

        return response()->json([
            'message' => 'Search results retrieved successfully',
            'query' => $query,
            'services' => $services
        ]);
    }

    public function getServicesByType(string $type, int $perPage = 15): JsonResponse
    {
        $services = $this->serviceRepository->getServicesByType($type, $perPage);

        return response()->json([
            'message' => 'Services retrieved successfully',
            'type' => $type,
            'services' => $services
        ]);
    }

    public function getServiceStats(int $specialistId): JsonResponse
    {
        $stats = $this->serviceRepository->getServiceStats($specialistId);

        return response()->json([
            'message' => 'Statistics retrieved successfully',
            'stats' => $stats
        ]);
    }

    public function validateServiceData(array $data): array
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1|max:480',
        ]);

        if ($validator->fails()) {
            return [
                'valid' => false,
                'errors' => $validator->errors()
            ];
        }

        return ['valid' => true, 'errors' => []];
    }
}
