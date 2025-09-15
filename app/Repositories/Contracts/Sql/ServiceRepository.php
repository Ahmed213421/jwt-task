<?php

namespace App\Repositories\Contracts\Sql;

use App\Models\Service;
use App\Repositories\Contracts\ServiceContract;
use Illuminate\Database\Eloquent\Model;

class ServiceRepository implements ServiceContract
{
    protected $model;
    public function __construct(Service $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['specialist'])->get();
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

    public function getServicesBySpecialist(int $specialistId, int $perPage = 15)
    {
        return $this->model
            ->with(['specialist'])
            ->where('specialist_id', $specialistId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getActiveServices(int $perPage = 15)
    {
        return $this->model
            ->with(['specialist'])
            ->whereHas('specialist', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getServicesByType(string $type, int $perPage = 15)
    {
        return $this->model
            ->with(['specialist'])
            ->whereHas('specialist', function ($query) use ($type) {
                $query->where('type', $type)
                      ->where('is_active', true);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function searchServices(string $query, int $perPage = 15)
    {
        return $this->model
            ->with(['specialist'])
            ->where('title', 'like', '%' . $query . '%')
            ->whereHas('specialist', function ($q) {
                $q->where('is_active', true);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getServiceStats(int $specialistId): array
    {
        return [
            'total_services' => $this->model->where('specialist_id', $specialistId)->count(),
            'active_services' => $this->model->where('specialist_id', $specialistId)->count(),
            'average_price' => $this->model->where('specialist_id', $specialistId)->avg('price'),
            'total_duration' => $this->model->where('specialist_id', $specialistId)->sum('duration'),
        ];
    }
}
