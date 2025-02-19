<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KycResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->kyc_id,
            'user_id ' => $this->user_id ,
            'person_name' => $this->person_name,
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'dob' => $this->dob,
            'nationality' => $this->nationality,
            'source_type' => $this->source_type,
            'kyc_type' => $this->kyc_type,
            'approve_status' => $this->approve_status,
            'invested_amount' => $this->invested_amount,
            'investment_period' => $this->investment_period,
            'kyc_document' => $this->kyc_document ? url($this->kyc_document) : null,
            'is_active' => $this->is_active,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString()
        ];
    }
}
