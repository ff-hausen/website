<?php

namespace App\Filament\Resources\Calendar\TypeResource\Pages;

use App\Facades\ColorContrast;
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['background_color']) {
            $data['text_color'] = ColorContrast::findTextColor($data['background_color']);
        }

        return $data;
    }


}
