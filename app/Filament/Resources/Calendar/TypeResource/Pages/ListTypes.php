<?php

namespace App\Filament\Resources\Calendar\TypeResource\Pages;

use App\Filament\Resources\Calendar\TypeResource;
use App\Models\Calendar\Department;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTypes extends ListRecords
{
    protected static string $resource = TypeResource::class;

    public function getTabs(): array
    {
        return Department::getList('view')
            ->mapWithKeys(fn (Department $item) => [$item->value => Tab::make($item->name)->modifyQueryUsing(
                fn (Builder $query): Builder => $query->where('department', $item->value)
            )])->toArray();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn () => Department::from($this->activeTab))
                ->url(fn () => route('filament.admin.resources.calendar.types.create', [
                    'department' => $this->activeTab,
                ])),
        ];
    }
}
