<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'verified',
        'primary',
    ];

    public function typeLocale(): string
    {
        return match ($this->type) {
            'ea' => 'Einsatzabteilung',
            'verein' => 'Verein/Freunde',
        };
    }

    public function price(): int
    {
        switch ($this->type) {
            case 'ea':
                return 90;
            case 'verein':
            default:
                return 150;
        }
    }
}
