<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use Uri\Rfc3986\Uri;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'email_verified_at',
        'user_approved_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected $appends = [
        'full_name',
        'avatar_url',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can('admin-panel:access');
    }

    public function getFilamentName(): string
    {
        return $this->full_name;
    }

    public function isUserApproved(): bool
    {
        return $this->user_approved_at !== null;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'user_approved_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(
            fn ($value, array $attributes) => trim("{$attributes['first_name']} {$attributes['last_name']}"),
        );
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value, array $attributes) {
                $hash = \hash('sha256', $attributes['email']);

                return new Uri('https://api.dicebear.com/9.x/thumbs/svg')
                    ->withQuery(http_build_query([
                        'seed' => $hash,
                        'shapeColor' => 'B1121A,D62828,FF3B30,C44536,8C1D18,5A0F0A,F06A4B,9E2A2B',
                    ]))->toString();
            },
        );
    }
}
