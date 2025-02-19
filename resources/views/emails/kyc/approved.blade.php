@component('mail::message')
# KYC Verification Approved

Dear {{ $kyc->user->name }},

Your KYC verification has been approved. You now have full access to all platform features.

Thanks,<br>
{{ config('app.name') }}
@endcomponent