<?php

namespace App\Repositories\Contracts;

interface ServiceContract extends BaseContract
{
    public function getSpecialistServices(int $specialistId, int $perPage = 15): array;
    public function getActiveServices(int $perPage = 15): array;
    public function getServicesByType(string $type, int $perPage = 15): array;
    public function searchServices(string $query, int $perPage = 15): array;
    public function getServiceStats(int $specialistId): array;
}
