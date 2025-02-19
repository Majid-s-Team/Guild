<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;

class ApiSuccessResponse implements Responsable
{
    public function __construct(
        protected mixed $data,
        protected string $message = '',
        protected array $metadata = [],
        protected int $code = Response::HTTP_OK,
        protected array $headers = []
    )
    {}

    public function toResponse($request)
    {
        return response()->json([
            'data' => $this->data,
            'message' => $this->message,
            'metadata' => $this->metadata,
        ], $this->code, $this->headers);
    }
}
