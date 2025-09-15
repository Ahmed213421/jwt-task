<?php

namespace App\Repositories\Contracts;

interface ServiceContract extends BaseContract
{
    public function getServicesBySpecialist(int $specialistId, int $perPage = 15);
    public function getActiveServices(int $perPage = 15);
    public function getServicesByType(string $type, int $perPage = 15);
    public function searchServices(string $query, int $perPage = 15);
    public function getServiceStats(int $specialistId): array;
}
