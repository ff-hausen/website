<?php

namespace App\Filament\Resources\Calendar;

use App\Facades\ColorContrast;
use App\Filament\Resources\Calendar\TypeResource\Pages;
use App\Models\Calendar\Department;
use App\Models\Calendar\Type;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Tomloprod\Colority\Support\Facades\Colority;
use UnitEnum;

class TypeResource extends Resource
{
    protected static ?string $model = Type::class;

    protected static ?string $slug = 'calendar/types';

    protected static string|UnitEnum|null $navigationGroup = 'Dienstplan';

    protected static ?string $navigationParentItem = 'Termine';

    protected static ?string $navigationLabel = 'Veranstaltungsart';

    protected static ?string $modelLabel = 'Veranstaltungsart';

    protected static ?string $pluralModelLabel = 'Veranstaltungsarten';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Tag;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                        if (filled($state) && ! filled($get('background_color'))) {
                            $backgroundColor = Colority::textToColor($state)->toHex()->getValueColor();
                            $set('background_color', $backgroundColor);
                            $set('text_color', ColorContrast::findTextColor($backgroundColor));
                        }
                    }),

                Select::make('department')
                    ->translateLabel()
                    ->options(function () {
                        return Department::getList('create')
                            ->mapWithKeys(fn (Department $item) => [$item->value => $item->getLabel()]);
                    })
                    ->default(fn () => request()->get('department')),

                ColorPicker::make('background_color')
                    ->translateLabel()
                    ->nullable()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (filled($state)) {
                            $set('text_color', ColorContrast::findTextColor($state));
                        }
                    }),

                ColorPicker::make('text_color')
                    ->translateLabel()
                    ->disabled()
                    ->dehydrated(),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->translateLabel()
                    ->state(fn (?Type $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->translateLabel()
                    ->state(fn (?Type $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->badge()->color(fn (Type $record) => Color::hex($record->background_color)),
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
            'index' => Pages\ListTypes::route('/'),
            'create' => Pages\CreateType::route('/create'),
            'edit' => Pages\EditType::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
