<?php

namespace App\Services;

use App\Models\Service;
use App\Repositories\Contracts\ServiceContract;
use Illuminate\Support\Facades\Validator;

class ServiceService
{
    protected $serviceRepository;

    public function __construct(ServiceContract $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function createService(array $data, int $specialistId): array
    {
        $validation = $this->validateServiceData($data);
        if (!$validation['valid']) {
            return [
                'error' => 'Validation failed',
                'errors' => $validation['errors']
            ];
        }

        $service = $this->serviceRepository->create([
            'specialist_id' => $specialistId,
            'title' => $data['title'],
            'price' => $data['price'],
            'duration' => $data['duration']
        ]);

        $service->load(['specialist']);

        return $service->toArray();
    }

    public function updateService(int $serviceId, array $data, int $specialistId): array
    {
        $service = $this->serviceRepository->find($serviceId);

        if (!$service) {
            return ['error' => 'Service not found'];
        }

        if ($service->specialist_id !== $specialistId) {
            return ['error' => 'Unauthorized access to service'];
        }

        $validation = $this->validateServiceData($data);
        if (!$validation['valid']) {
            return [
                'error' => 'Validation failed',
                'errors' => $validation['errors']
            ];
        }

        $this->serviceRepository->update($service, $data);
        $service->refresh();
        $service->load(['specialist']);

        return $service->toArray();
    }

    public function deleteService(int $serviceId, int $specialistId): array
    {
        $service = $this->serviceRepository->find($serviceId);

        if (!$service) {
            return ['error' => 'Service not found'];
        }

        if ($service->specialist_id !== $specialistId) {
            return ['error' => 'Unauthorized access to service'];
        }

        $this->serviceRepository->remove($service);

        return ['deleted' => true];
    }

    public function getSpecialistServices(int $specialistId, int $perPage = 15): array
    {
        return $this->serviceRepository->getSpecialistServices($specialistId, $perPage);
    }

    public function getAllServices(int $perPage = 15): array
    {
        return $this->serviceRepository->all()->toArray();
    }

    public function getService(int $serviceId): array
    {
        $service = $this->serviceRepository->find($serviceId, ['specialist']);

        if (!$service) {
            return ['error' => 'Service not found'];
        }

        return $service->toArray();
    }


    public function getServiceStats(int $specialistId): array
    {
        return $this->serviceRepository->getServiceStats($specialistId);
    }

    public function validateServiceData(array $data): array
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
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
