<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    public $fillable = ['message', 'type', 'before', 'after', 'created_by', 'loggable_type', 'loggable_id'];
}
