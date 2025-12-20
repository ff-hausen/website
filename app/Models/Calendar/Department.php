<?php

namespace App\Models\Calendar;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;

enum Department: string implements HasLabel
{
    case Einsatzabteilung = 'ea';

    case Jugendfeuerwehr = 'jf';

    case Minifeuerwehr = 'mf';

    public static function getList(string $permission): Collection
    {
        return collect(self::cases())
            ->filter(fn (Department $item) => $item->allowedTo($permission));
    }

    public function allowedTo(string $permission): bool
    {
        return auth()->user()->can("calendar:{$permission}:{$this->value}");
    }

    public function getLabel(): string|Htmlable|null
    {
        return $this->name;
    }
}
