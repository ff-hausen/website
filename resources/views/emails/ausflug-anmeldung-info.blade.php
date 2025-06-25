@component('mail::message')
# Neue Anmeldung zum Vereinsausflug

Die folgenden Teilnehmer:innen wurden angemeldet:

@component('components.ausflug-participant-list', ['participants' => $participants])
@endcomponent

@component('mail::button', ['url' => $url])
Zur Übersichtsseite
@endcomponent
@endcomponent
