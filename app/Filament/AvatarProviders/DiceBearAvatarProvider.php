<?php

namespace App\Filament\AvatarProviders;

use App\Models\User;
use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class DiceBearAvatarProvider implements AvatarProvider
{
    public function get(Model $record): string
    {
        $name = $record instanceof User
            ? $record->username
            : Filament::getNameForDefaultAvatar($record);

        $query = http_build_query([
            'seed' => $name,
            'radius' => 50,
            'shapeColor' => ['b91c1c', '991b1b', '7f1d1d'],
            'backgroundColor' => ['fee2e2', 'fecaca', 'fca5a5', 'f87171'],
        ]);

        return 'https://api.dicebear.com/9.x/thumbs/svg?'.$query;
    }
}
