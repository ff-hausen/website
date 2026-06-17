@component('mail::message')
# Bestätigung deiner Anmeldung zum Vereinsausflug

Vielen Dank für deine Anmeldung. Diese ist hiermit bestätigt.

Die folgenden Teilnehmer:innen wurden angemeldet:

@component('components.ausflug-participant-list', ['participants' => $participants])
@endcomponent

**Gesamtbetrag: {{ $participants->reduce(fn($c, $p) => $c+$p->price, 0) }} €**

## Zahlungsinformationen

Bitte überweise den Gesamtbetrag bis spätestens 31.07.2026 auf folgendes Konto:

| | |
|---|---|
| **Bank** | Frankfurter Sparkasse |
| **Empfänger** | Freiwillige Feuerwehr Frankfurt-Hausen e.V. |
| **IBAN** | DE51 5005 0201 0000 3191 29 |
| **BIC** | HELADEF1822 |

@component('mail::button', ['url' => $url])
Zur Übersichtsseite
@endcomponent
@endcomponent
