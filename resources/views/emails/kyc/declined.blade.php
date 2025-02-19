@component('mail::message')
# KYC Verification Declined

Dear {{ $kyc->user->name }},

Your KYC verification has been declined for the following reason:

{{ $reason }}

If you have any questions, please contact our support team at {{ $support_email }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent