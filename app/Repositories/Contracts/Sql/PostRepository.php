<?php

namespace App\Repositories\Contracts\Sql;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\Contracts\PostContract;
use finfo;
use Illuminate\Database\Eloquent\Model;

class PostRepository implements PostContract{
    public function __construct(Post $model)
    {
        $this->model = $model;
    }


    public function all(){
        return new PostResource($this->model->all());
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
}
