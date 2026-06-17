<?php

namespace App\Mail\Ausflug;

use App\Models\AusflugParticipant;
use App\Support\EpcPaymentQrCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class AnmeldungConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected string $submissionId;

    /**
     * @param  Collection<AusflugParticipant>  $participants
     */
    public function __construct(protected Collection $participants)
    {
        $this->submissionId = $this->participants[0]->submission_id;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bestätigung deiner Anmeldung zum Vereinsausflug',
        );
    }

    public function content(): Content
    {
        $amount = (float) $this->participants->sum('price');
        $primary = $this->participants->where('primary', true)->first();

        return new Content(
            markdown: 'emails.ausflug.anmeldung-confirmation',
            with: [
                'participants' => $this->participants,
                'url' => URL::signedRoute('ausflug.summary', ['submissionId' => $this->submissionId]),
                'epcQrCodeDataUri' => EpcPaymentQrCode::makeDataUri(
                    recipient: 'Freiwillige Feuerwehr Frankfurt-Hausen e.V.',
                    iban: 'DE51500502010000319129',
                    amount: $amount,
                    bic: 'HELADEF1822',
                    remittanceInformation: 'Vereinsausflug '.$primary->name,
                ),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
