<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesAgentVisit extends Model
{
    use HasFactory;

    protected $fillable = ['agent_id', 'user_ip'];
}
