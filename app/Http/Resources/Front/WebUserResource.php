<?php

namespace App\Http\Resources\Front;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'nationality' => $this->nationality,
            'dob' => $this->dob,
            'referral_source' => $this->referral_source,
            'is_active' => $this->is_active
            //'user_type' => $this->user_type
        ];
    }
}
