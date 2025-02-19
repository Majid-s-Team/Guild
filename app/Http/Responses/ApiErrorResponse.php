<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Throwable;
use function Psy\debug;

class ApiErrorResponse implements Responsable
{

    public function __construct(

        protected string $message,

        protected mixed $e = null,

        protected int $code = Response::HTTP_INTERNAL_SERVER_ERROR,

        protected array $headers = []
    )
    {}

    public function toResponse($request)
    {
        $responses = [

            'message' => $this->message,
        ];

        if ($this->e && $this->e instanceof Throwable && config('app.debug')) {
            $responses['debug'] = [
                'message' => $this->e->getMessage(),
                'line' => $this->e->getLine(),
                'file' => $this->e->getFile()
            ];
        } else {

            $responses['debug'] = ['message' => $this->e];
        }

        return response()->json($responses, $this->code, $this->headers);
    }
}
