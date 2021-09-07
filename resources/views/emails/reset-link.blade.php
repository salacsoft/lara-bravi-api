@component('mail::message')
# Forgot Password

Hi {{$full_name}},

You request for forgot password link.
Click the button if you want to reset your password. Thank you!

If you are not the one who request this, we advice that you enable the two factor authentication
to secure your account.


@component('mail::button', ['url' => $url])
Reset my password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
