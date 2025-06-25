@component('mail::message')
# Bestätigung deiner Anmeldung zum Vereinsausflug

Vielen Dank für deine Anmeldung. Diese ist hiermit bestätigt.

Die folgenden Teilnehmer:innen wurden angemeldet:

@component('components.ausflug-participant-list', ['participants' => $participants])
@endcomponent

**Gesamtbetrag: {{ $participants->reduce(fn($c, $p) => $c+$p->price, 0) }} €**

Überweise den Gesamtbetrag bitte bis spätestens 01.08.2025 auf folgendes Konto:

Frankfurter Sparkasse \
IBAN: DE51 5005 0201 0000 3191 29 \
BIC: HELADEF1822

@component('mail::button', ['url' => $url])
Zur Übersichtsseite
@endcomponent
@endcomponent
