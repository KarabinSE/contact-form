<x-mail::message>
# Hej!

Vi har tagit emot ditt meddelande, vi återkommer så fort vi kan.

@if(config('contact-form.include_app_name'))
Med vänliga hälsningar,<br>
{{ config('app.name') }}
@endif

</x-mail::message>
