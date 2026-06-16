<?php

namespace App\Filament\Resources\AusflugParticipants\Pages;

use App\Filament\Resources\AusflugParticipants\AusflugParticipantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAusflugParticipants extends ListRecords
{
    protected static string $resource = AusflugParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
