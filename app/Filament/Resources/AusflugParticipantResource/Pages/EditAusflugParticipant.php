<?php

namespace App\Filament\Resources\AusflugParticipantResource\Pages;

use App\Filament\Resources\AusflugParticipantResource;
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
