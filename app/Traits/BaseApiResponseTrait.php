<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait BaseApiResponseTrait
{
    protected ?int $statusCode = null;

    /**
     * setStatusCode() set status code value
     *
     * @return $this
     */
    protected function setStatusCode($statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * respondWithArray() used to return json response array with status and headers
     */
    protected function respondWithArray($data, array $headers = []): JsonResponse
    {
        return response()->json($data, $data['status'], $headers);
    }

    /**
     * getStatusCode() return status code value
     */
    protected function getStatusCode(): int
    {
        return $this->statusCode ?: Response::HTTP_OK;
    }


    /**
     * respondWithSuccess() used to return success message
     */
    protected function respondWithSuccess(?string $message = null, $data = []): JsonResponse
    {
        $response = [
            'status' => Response::HTTP_OK,
        ];
        $response['message'] = !empty($message) ? $message : 'success';
        if (!empty($data)) {
            $response['data'] = $data;
        }

        return $this->setStatusCode(Response::HTTP_OK)->respondWithArray($response);
    }

    /**
     * respondWithError() used to return error message
     */
    protected function respondWithError($message, int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return $this->respondWithErrors($message, $statusCode);
    }

    protected function respondWithErrors(
        string $errors = 'messages.error',
        $statusCode = null,
        array $data = [],
    ): JsonResponse {
        $statusCode = !empty($statusCode) ? $statusCode : Response::HTTP_INTERNAL_SERVER_ERROR;
        if (is_string($errors)) {
            $errors = __($errors);
        }
        $response = ['status' => $statusCode, 'errors' => ['message' => [$errors]]];
        if (!empty($message)) {
            $response['message'] = $message;
        }
        if (!empty($data)) {
            $response['data'] = $data;
        }

        return $this->setStatusCode($statusCode)->respondWithArray($response);
    }


}
