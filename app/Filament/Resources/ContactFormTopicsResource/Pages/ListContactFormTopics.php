<?php

namespace App\Filament\Resources\ContactFormTopicsResource\Pages;

use App\Filament\Resources\ContactFormTopicsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContactFormTopics extends ListRecords
{
    protected static string $resource = ContactFormTopicsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
