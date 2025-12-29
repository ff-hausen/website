<?php

namespace App\Filament\Resources\Calendar;

use App\Filament\Resources\Calendar\EventResource\Pages;
use App\Models\Calendar\Event;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $slug = 'calendar/events';

    protected static string|UnitEnum|null $navigationGroup = 'Dienstplan';

    protected static ?string $navigationLabel = 'Termine';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    protected static ?string $modelLabel = 'Termin';

    protected static ?string $pluralModelLabel = 'Termine';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),

                TextInput::make('description')
                    ->required(),

                DatePicker::make('starts_at')
                    ->label('Starts Date'),

                DatePicker::make('ends_at')
                    ->label('Ends Date'),

                Checkbox::make('is_all_day'),

                TextInput::make('department')
                    ->required(),

                Select::make('type_id')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->required(),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->state(fn (?Event $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->state(fn (?Event $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description'),

                TextColumn::make('starts_at')
                    ->label('Starts Date')
                    ->date(),

                TextColumn::make('ends_at')
                    ->label('Ends Date')
                    ->date(),

                TextColumn::make('is_all_day'),

                TextColumn::make('department'),

                TextColumn::make('type.name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['type']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'type.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->type) {
            $details['Type'] = $record->type->name;
        }

        return $details;
    }
}
