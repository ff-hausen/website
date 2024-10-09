<?php

namespace App\Filament\Resources\Calendar\EventResource\Pages;

use App\Filament\Resources\Calendar\EventResource;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->user()->id;

        if ($data['all_day']) {
            $data['start_time'] = Carbon::parse($data['start_time'])->setTime(0, 0)->toDateTimeLocalString();
            $data['end_time'] = Carbon::parse($data['end_time'])->setTime(0, 0)->toDateTimeLocalString();
        }

        return $data;
    }
}
