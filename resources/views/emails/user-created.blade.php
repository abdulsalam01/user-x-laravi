@component('mail::message')
# Welcome, {{ $user->name }}!

Your account has been created successfully.

**Email:** {{ $user->email }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent