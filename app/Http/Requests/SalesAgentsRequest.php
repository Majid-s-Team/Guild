<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesAgentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:20',
            'fixed_commission_amount' => 'nullable|string',
            'commission_type' => 'nullable|string',
            'is_active' => 'required|boolean',
            'region' => 'nullable|string',
            'image' => 'nullable|image',
            'zcal_meeting_link' => 'nullable',
            'whatsApp_number' => 'nullable',
            'guild_email_address' => 'nullable',
            'bank_name' => 'nullable',
            'account_number' => 'nullable',
            'iban_number' => 'nullable',
            'identification_document' => 'nullable',
            'agent_agreement' => 'nullable',
        ];
    }
}
