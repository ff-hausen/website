<?php

namespace App\Filament\Resources\ContactFormTopicsResource\Pages;

use App\Filament\Resources\ContactFormTopicsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContactFormTopics extends EditRecord
{
    protected static string $resource = ContactFormTopicsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
