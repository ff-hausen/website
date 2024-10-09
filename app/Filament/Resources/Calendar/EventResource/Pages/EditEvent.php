<?php

namespace App\Filament\Resources\Calendar\EventResource\Pages;

use App\Filament\Resources\Calendar\EventResource;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['all_day']) {
            $data['start_time'] = Carbon::parse($data['start_time'])->setTime(0, 0)->toDateTimeLocalString();
            $data['end_time'] = Carbon::parse($data['end_time'])->setTime(0, 0)->toDateTimeLocalString();
        }

        return $data;
    }
}
