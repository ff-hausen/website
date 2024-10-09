<?php

namespace App\Models\Calendar;

use App\Models\RoleName;
use App\Models\User;
use Filament\Support\Contracts\HasLabel;

enum Department: string implements HasLabel
{
    case Einsatzabteilung = 'ea';

    case Jugendfeuerwehr = 'jf';

    case Minifeuerwehr = 'mf';

    public function getLabel(): ?string
    {
        return match ($this->value) {
            'ea' => 'Einsatzabteilung',
            'jf' => 'Jugendfeuerwehr',
            'mf' => 'Minifeuerwehr',
        };
    }

    public function canCreate(User $user): bool
    {
        if ($user->hasRole(RoleName::Wehrfuehrung) || $user->hasRole(RoleName::Administrator)) {
            return true;
        }

        return match ($this->value) {
            'jf' => $user->hasRole(RoleName::JugendfeuerwehrLeitung),
            'mf' => $user->hasRole(RoleName::MinifeuerwehrLeitung),
            default => false,
        };
    }
}
