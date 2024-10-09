<?php

namespace App\Filament\Resources\Calendar\TypeResource\Pages;

use App\Filament\Resources\Calendar\TypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateType extends CreateRecord
{
    protected static string $resource = TypeResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
