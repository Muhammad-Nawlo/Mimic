@component('mail::message')
# Introduction
Hello  : {{$name}}

Copy This Code And paste It In  Mimic To Reset Your Password

{{$token}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
