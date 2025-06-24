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
    ];
}
