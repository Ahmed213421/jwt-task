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
        $result = $this->serviceService->getAllServices($perPage);

        if ($result->getStatusCode() === 200) {
            $data = $result->getData(true);
            return $this->respondWithSuccess('Services retrieved successfully', $data);
        }

        return $result;
    }

    public function show(int $serviceId)
    {
        $result = $this->serviceService->getService($serviceId);

        if ($result->getStatusCode() === 200) {
            $data = $result->getData(true);
            return $this->respondWithSuccess('Service retrieved successfully', $data);
        }

        return $result;
    }

    public function store(Request $request)
    {
        $specialist = $request->user();
        $result = $this->serviceService->createService($request->all(), $specialist->id);

        if ($result->getStatusCode() === 201) {
            $data = $result->getData(true);
            return $this->setStatusCode(201)->respondWithSuccess('Service created successfully', $data);
        }

        return $result;
    }

    public function update(Request $request, int $serviceId)
    {
        $specialist = $request->user();
        $result = $this->serviceService->updateService($serviceId, $request->all(), $specialist->id);

        if ($result->getStatusCode() === 200) {
            $data = $result->getData(true);
            return $this->respondWithSuccess('Service updated successfully', $data);
        }

        return $result;
    }

    public function destroy(Request $request, int $serviceId)
    {
        $specialist = $request->user();
        $result = $this->serviceService->deleteService($serviceId, $specialist->id);

        if ($result->getStatusCode() === 200) {
            return $this->respondWithSuccess('Service deleted successfully');
        }

        return $result;
    }

    public function specialistServices(Request $request)
    {
        $specialist = $request->user();
        $perPage = $request->get('per_page', 15);
        $result = $this->serviceService->getSpecialistServices($specialist->id, $perPage);

        if ($result->getStatusCode() === 200) {
            $data = $result->getData(true);
            return $this->respondWithSuccess('Services retrieved successfully', $data);
        }

        return $result;
    }



    

    public function stats(Request $request)
    {
        $specialist = $request->user();
        $result = $this->serviceService->getServiceStats($specialist->id);

        if ($result->getStatusCode() === 200) {
            $data = $result->getData(true);
            return $this->respondWithSuccess('Statistics retrieved successfully', $data);
        }

        return $result;
    }
}
