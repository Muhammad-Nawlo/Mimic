@component('mail::message')
# This Code To Verify Your Account
Hello {{$user_name}}

Copy And Paste This Code To Mimic  To Verify Your Account

<b>{{$token}}</b>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
