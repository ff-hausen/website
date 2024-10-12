<?php

namespace App\Filament\Resources\Calendar\EventResource\Pages;

use App\Filament\Resources\Calendar\EventResource;
use App\Models\Calendar\Department;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(fn () => EventResource::getUrl('create', [
                    'department' => $this->activeTab,
                ])),
        ];
    }

    public function getTabs(): array
    {
        return collect(Department::cases())
            ->filter(fn (Department $item) => $item->canCreate(auth()->user()))
            ->mapWithKeys(function ($item) {
                return [
                    $item->value => Tab::make($item->getLabel())
                        ->modifyQueryUsing(fn (Builder $query) => $query->where('department', $item->value)),
                ];
            })->toArray();
    }
}
