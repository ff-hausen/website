<?php

namespace App\Filament\Resources\AusflugParticipantResource\Pages;

use App\Filament\Resources\AusflugParticipantResource;
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
