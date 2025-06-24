<?php

namespace App\Mail;

use App\Models\AusflugParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class AusflugAnmeldungVerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(protected string $submissionId) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vereinsausflug Anmeldung',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ausflug-anmeldung-verification',
            with: [
                'participants' => AusflugParticipant::whereSubmissionId($this->submissionId)->get(),
                'url' => URL::signedRoute('ausflug.verification', ['submissionId' => $this->submissionId]),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
