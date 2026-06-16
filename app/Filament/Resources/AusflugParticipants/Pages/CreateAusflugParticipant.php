<?php

namespace App\Filament\Resources\AusflugParticipants\Pages;

use App\Filament\Resources\AusflugParticipants\AusflugParticipantResource;
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
