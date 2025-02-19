<?php

namespace App\Services;

use App\Contracts\LogServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LogService implements LogServiceInterface
{
    public function createLog(string $type, string $message, Model $model): void
    {
        $model->logs()->create([
            'type' => $type,
            'message' => $message,
            'user_id' => Auth('api')->id()
        ]);
    }
}
