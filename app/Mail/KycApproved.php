<?php

namespace App\Mail;

use App\Models\KYC;
use Illuminate\Mail\Mailable;

class KycApproved extends Mailable
{
    public function __construct(public KYC $kyc)
    {
    }

    public function build()
    {
        return $this->markdown('emails.kyc.approved')
                   ->subject('KYC Verification Approved');
    }
}
