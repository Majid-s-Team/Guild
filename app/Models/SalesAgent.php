<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesAgent extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function visits()
    {
        return $this->hasMany(SalesAgentVisit::class, 'agent_id');
    }
}
