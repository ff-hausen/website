<x-mail::message>
# Vereinsausflug Anmeldung

Teilnehmer:innen:

@foreach($participants as $participant)
- {{ $participant->name }} ({{ $participant->price }} €)
@endforeach

**Gesamtbetrag:** {{ $participants->reduce(fn($c, $p) => $p->price + $c, 0) }} €

Bitte bestätige deine Anmeldung mit dem Klick auf den folgenden Link:

<x-mail::button :url="$url" color="primary">
    Anmeldung bestätigen
</x-mail::button>
</x-mail::message>
