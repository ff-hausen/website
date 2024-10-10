<?php

namespace App\Filament\Resources\Calendar\TypeResource\Pages;

use App\Facades\ColorContrast;
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['background_color']) {
            $data['text_color'] = ColorContrast::findTextColor($data['background_color']);
        } else {
            $data['text_color'] = null;
        }

        return $data;
    }
}
