<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

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

    public function summaryUrl(): string
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
