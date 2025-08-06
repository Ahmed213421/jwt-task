<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseContract
{

    public function all();

    public function create(array $attributes = []): mixed;

    public function update(Model $model, array $attributes = []): mixed;


    public function remove(Model $model): mixed;

    public function find(int $id, array $relations = [], array $filters = []): mixed;

    public function findOrFail(int $id, array $relations = [], array $filters = []): mixed;


}
