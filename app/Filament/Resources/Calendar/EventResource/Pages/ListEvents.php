<?php

namespace App\Filament\Resources\Calendar\EventResource\Pages;

use App\Filament\Resources\Calendar\EventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    public function getTabs(): array
    {
        return [
            'ea' => Tab::make('Einsatzabteilung')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('department', 'ea')),
            'jf' => Tab::make('Jugendfeuerwehr')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('department', 'jf')),
            'mf' => Tab::make('Minifeuerwehr')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('department', 'mf')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
