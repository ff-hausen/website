<?php

namespace App\Filament\Resources\AusflugParticipants\Pages;

use App\Filament\Resources\AusflugParticipants\AusflugParticipantResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewAusflugParticipant extends ViewRecord
{
    protected static string $resource = AusflugParticipantResource::class;

    public function getRecordTitle(): string|Htmlable
    {
        return 'Anmeldung von '.$this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
