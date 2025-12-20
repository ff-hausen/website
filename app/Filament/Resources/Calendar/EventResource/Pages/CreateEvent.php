<?php

namespace App\Filament\Resources\Calendar\EventResource\Pages;

use App\Filament\Resources\Calendar\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
