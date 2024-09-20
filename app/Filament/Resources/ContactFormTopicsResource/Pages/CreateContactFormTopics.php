<?php

namespace App\Filament\Resources\ContactFormTopicsResource\Pages;

use App\Filament\Resources\ContactFormTopicsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContactFormTopics extends CreateRecord
{
    protected static string $resource = ContactFormTopicsResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
