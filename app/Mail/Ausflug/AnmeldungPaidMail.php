<?php

namespace App\Mail\Ausflug;

use App\Models\AusflugParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class AnmeldungPaidMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        protected string $submissionId,
        protected float $paidAmount,
        protected float $outstandingAmount,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vereinsausflug: Zahlung erhalten',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ausflug.anmeldung-paid',
            with: [
                'participants' => AusflugParticipant::whereSubmissionId($this->submissionId)->get(),
                'paidAmount' => $this->paidAmount,
                'outstandingAmount' => $this->outstandingAmount,
                'url' => URL::signedRoute('ausflug.summary', ['submissionId' => $this->submissionId]),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
