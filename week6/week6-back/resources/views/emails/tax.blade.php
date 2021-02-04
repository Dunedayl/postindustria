@component('mail::message')
# It's time to pay your tax


Dear {{ $firstname }} {{$lastname}} it's already {{ $date }}.
It's time to pay your tax.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
