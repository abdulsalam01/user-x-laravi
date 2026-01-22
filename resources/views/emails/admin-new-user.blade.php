@component('mail::message')
# New user created

A new user has registered.

**Name:** {{ $user->name }}  
**Email:** {{ $user->email }}  
**Role:** {{ $user->role }}  
**Active:** {{ $user->active ? 'Yes' : 'No' }}

@endcomponent