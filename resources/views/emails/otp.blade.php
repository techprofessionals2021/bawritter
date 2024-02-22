
@component('mail::message')

{{ $heading }}

{{ $content }}



@component('mail::button', ['url' => route('register')])
{{ $btn['text'] }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent



