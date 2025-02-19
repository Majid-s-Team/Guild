<?php
namespace App\Mail;

use App\Models\KYC;
use Illuminate\Mail\Mailable;

class KycDeclined extends Mailable
{
    public function __construct(public KYC $kyc)
    {
    }

    public function build()
    {
        return $this->markdown('emails.kyc.declined')
                   ->subject('KYC Verification Declined')
                   ->with([
                       'reason' => $this->kyc->decline_reason,
                       'support_email' => config('mail.support_address')
                   ]);
    }
}
