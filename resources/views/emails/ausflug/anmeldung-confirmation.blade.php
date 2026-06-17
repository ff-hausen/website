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

Scanne den Code in deiner Banking-App, um die Überweisung direkt vorauszufüllen.

<p style="text-align: center; margin: 16px 0;">
	<img src="{{ $epcQrCodeDataUri }}" alt="EPC Zahlungs-QR-Code" width="220" height="220">
</p>

@component('mail::button', ['url' => $url])
Zur Übersichtsseite
@endcomponent
@endcomponent
