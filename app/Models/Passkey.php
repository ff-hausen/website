<?php

namespace App\Models;

use App\Casts\PublicKeyCredentialSourceCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Passkey extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'credential_id',
        'data',
    ];

    protected $visible = [
        'id',
        'name',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => PublicKeyCredentialSourceCast::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
