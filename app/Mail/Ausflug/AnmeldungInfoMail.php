<?php

namespace App\Mail\Ausflug;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class AnmeldungInfoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected string $submissionId;

    public function __construct(protected Collection $participants)
    {
        $this->submissionId = $this->participants[0]->submission_id;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Neue Anmeldung zum Vereinsausflug',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ausflug.anmeldung-info',
            with: [
                'participants' => $this->participants,
                'url' => URL::signedRoute('ausflug.verification', ['submissionId' => $this->submissionId]),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
