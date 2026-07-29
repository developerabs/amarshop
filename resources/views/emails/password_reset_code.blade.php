Dear Customer,

A request has been received to reset the password for your {{ config('app.name') }} account.

Your password reset link is provided below. Please click the link to reset your password:

**********************
      {{ env('FRONTEND_URL') }}/password-reset?code={{ $code }}&&email={{ $email }}
**********************

For your security:

- This code is valid for 10 minutes.
- Do not share this code with anyone.
- Our team will never ask for your verification code.

If you did not request this password reset, you can safely ignore this email.

Regards,

{{ config('app.name') }}
Support Team