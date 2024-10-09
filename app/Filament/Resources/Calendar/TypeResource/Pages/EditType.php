<?php

namespace App\Filament\Resources\Calendar\TypeResource\Pages;

use App\Filament\Resources\Calendar\TypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditType extends EditRecord
{
    protected static string $resource = TypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
