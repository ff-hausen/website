<?php

namespace App\Filament\Resources\AusflugParticipants\Pages;

use App\Filament\Resources\AusflugParticipants\AusflugParticipantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAusflugParticipant extends EditRecord
{
    protected static string $resource = AusflugParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
