<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class Controller
{

    protected function resApi(
        null|array $data = [],
        int $status = Response::HTTP_OK,
        null|array $errors = [],
        null|string $message = null
    ) {

        return new JsonResponse([
            'data' => $data,
            'status' => $status,
            'errors' => $errors,
            'message' => $message
        ], $status);
    }
}
