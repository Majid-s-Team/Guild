<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class KYC extends Model
{
    protected $table = 'kycs';
    protected $primaryKey = 'kyc_id';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
