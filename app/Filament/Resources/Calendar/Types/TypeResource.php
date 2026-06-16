<?php

namespace App\Filament\Resources\Calendar\Types;

use App\Filament\Resources\Calendar\Events\EventResource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Calendar\Types\Pages\ListTypes;
use App\Filament\Resources\Calendar\Types\Pages\CreateType;
use App\Filament\Resources\Calendar\Types\Pages\EditType;
use App\Facades\ColorContrast;
use App\Filament\Resources\Calendar\TypeResource\Pages;
use App\Models\Calendar\Type;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TypeResource extends Resource
{
    protected static ?string $model = Type::class;

    protected static ?string $slug = 'calendar/types';

    protected static string | \UnitEnum | null $navigationGroup = 'Dienstplan';

    protected static ?string $navigationParentItem = 'Termine';

    protected static ?string $navigationLabel = 'Veranstaltungsart';

    protected static ?string $modelLabel = 'Veranstaltungsart';

    protected static ?string $pluralModelLabel = 'Veranstaltungsarten';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->autofocus(),

                Select::make('department')
                    ->translateLabel()
                    ->options(EventResource::departmentOptions())
                    ->default(fn () => request()->query('department'))
                    ->required(),

                ColorPicker::make('background_color')
                    ->live()
                    ->nullable()
                    ->afterStateUpdated(fn ($state, Set $set) => $state ? $set('text_color', ColorContrast::findTextColor($state)) : $set('text_color', null)),

                ColorPicker::make('text_color')
                    ->disabled(),

                Placeholder::make('created_at')
                    ->content(fn (?Type $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->content(fn (?Type $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTypes::route('/'),
            'create' => CreateType::route('/create'),
            'edit' => EditType::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
