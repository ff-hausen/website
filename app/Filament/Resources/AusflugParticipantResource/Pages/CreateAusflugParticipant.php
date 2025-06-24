<?php

namespace App\Filament\Resources\AusflugParticipantResource\Pages;

use App\Filament\Resources\AusflugParticipantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAusflugParticipant extends CreateRecord
{
    protected static string $resource = AusflugParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
