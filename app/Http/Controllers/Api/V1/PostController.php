<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\Contracts\PostContract;
use Flasher\Prime\Notification\Notification;
use Illuminate\Http\Request;

class PostController extends BaseApiController
{

    public function __construct(PostContract $repository)
    {
        parent::__construct($repository, PostResource::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = $this->repository->all();
        return $this->respondWithCollection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        try {
            $post = $this->repository->create($request->validated());

            return $this->respondWithSuccess(
                'Saved Successfully',
                [new PostResource($post)]
            );
        } catch (\Exception $exception) {
            return $this->respondWithError(
            $exception->getMessage(),
            500
        );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    try {
        $post = $this->repository->findOrFail($id);
        return $this->respond(new PostResource($post));
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return $this->respondWithError('Post not found', 404);
    }
}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {

        try {
            $post = $this->repository->update($post,$request->validated());

            return $this->respondWithSuccess(
                'Saved Successfully',
                [new PostResource($post)]
            );
         } catch (\Exception $exception) {
            return $this->respondWithError(
            $exception->getMessage(),
            500
        );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try{
            $this->repository->remove($post);
            return $this->respondWithSuccess('Post deleted successfully');
        } catch (\Exception $exception) {
            return $this->respondWithError('Something went wrong');
        }
    }
}
