@component('mail::message')
    # Welcome, {{ $user->name }}

    Thank you for registering on our platform.

    @component('mail::button', ['url' => url('/')])
        Visit Our Site
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
