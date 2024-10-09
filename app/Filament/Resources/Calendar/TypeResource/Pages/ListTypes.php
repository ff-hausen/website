<?php

namespace App\Filament\Resources\Calendar\TypeResource\Pages;

use App\Filament\Resources\Calendar\TypeResource;
use App\Models\Calendar\Department;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTypes extends ListRecords
{
    protected static string $resource = TypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(fn () => route('filament.admin.resources.calendar.types.create', [
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
