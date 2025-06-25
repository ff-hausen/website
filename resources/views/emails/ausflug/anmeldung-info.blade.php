@component('mail::message')
# Neue Anmeldung zum Vereinsausflug

Die folgenden Teilnehmer:innen wurden angemeldet:

@component('components.ausflug-participant-list', ['participants' => $participants])
@endcomponent

**Gesamtbetrag: {{ $participants->reduce(fn($c, $p) => $c+$p->price(), 0) }} €**

@component('mail::button', ['url' => $url])
Zur Übersichtsseite
@endcomponent
@endcomponent
