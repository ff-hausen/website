@component('mail::message')
# Zahlung zum Vereinsausflug erhalten

Hallo,

vielen Dank — wir haben deine Zahlung in Höhe von {{ number_format($paidAmount, 2) }} € erhalten.

Der noch offene Betrag beträgt {{ number_format($outstandingAmount ?? 0, 2) }} €.

@component('mail::button', ['url' => $url])
Übersichtsseite
@endcomponent
@endcomponent
