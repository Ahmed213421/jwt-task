<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BaseContract;
use App\Traits\BaseApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends Controller
{
    use BaseApiResponseTrait;

    protected BaseContract $repository;

    protected mixed $modelResource;

    /**
     * BaseApiController constructor.
     */
    public function __construct(BaseContract $repository, mixed $modelResource)
    {
        $this->repository = $repository;
        $this->modelResource = $modelResource;

    }

    /**
     * respond() used to return resource with status and headers
     */
    protected function respond($resources, array $headers = []): mixed
    {
        return $resources
            ->additional(['status' => $this->getStatusCode()])
            ->response()
            ->setStatusCode($this->getStatusCode())
            ->withHeaders($headers);
    }

    protected function respondWithCollection($collection, ?int $statusCode = null, array $headers = []): mixed
    {
        $statusCode = $statusCode ?? Response::HTTP_OK;
        $resourceClass = $this->modelResource;
        $resources = $resourceClass::collection($collection);
        return $this->setStatusCode($statusCode)->respond($resources, $headers);
    }


    

}
