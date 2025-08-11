<?php

namespace App\Models;

use App\Mail\Ausflug\AnmeldungConfirmationMail;
use App\Mail\Ausflug\AnmeldungInfoMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AusflugParticipant extends Model
{
    protected $fillable = [
        'submission_id',
        'name',
        'street',
        'zip_code',
        'city',
        'email',
        'phone',
        'type',
        'price',
        'verified',
        'primary',
    ];

    protected static function booted(): void
    {
        static::creating(function (AusflugParticipant $participant) {
            if (! $participant->submission_id) {
                $participant->submission_id = Str::uuid7();
            }
        });
    }

    public function verifyRegistration(): void
    {
        $participants = AusflugParticipant::whereSubmissionId($this->submission_id)->get();

        $affectedRows = $participants
            ->update([
                'verified' => true,
            ]);

        if ($affectedRows > 0) {
            // Send confirmation email to all participants
            $participants->whereNotNull('email')->each(fn ($p) => Mail::to($p->email)->send(new AnmeldungConfirmationMail($participants)));

            // Send notification to board
            $infoRecipients = explode(',', config('verein-ausflug.info_recipients'));
            Mail::to($infoRecipients)->send(new AnmeldungInfoMail($participants));
        }
    }

    public function summaryUrl(): string
    {
        return URL::signedRoute('ausflug.summary', ['submissionId' => $this->submission_id]);
    }

    public function verificationUrl(): string
    {
        return URL::signedRoute('ausflug.verification', ['submissionId' => $this->submission_id]);
    }

    public function typeLocale(): string
    {
        return match ($this->type) {
            'ea' => 'Einsatzabteilung',
            'verein' => 'Verein/Freunde',
        };
    }
}
