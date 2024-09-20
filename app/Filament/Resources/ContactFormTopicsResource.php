<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactFormTopicsResource\Pages;
use App\Models\ContactFormTopics;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use IbrahimBougaoua\FilamentSortOrder\Actions\DownStepAction;
use IbrahimBougaoua\FilamentSortOrder\Actions\UpStepAction;

class ContactFormTopicsResource extends Resource
{
    protected static ?string $model = ContactFormTopics::class;

    protected static ?string $modelLabel = 'Kontaktformular Thema';

    protected static ?string $pluralModelLabel = 'Kontaktformular Themen';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $navigationLabel = 'Kontaktformular Themen';

    protected static ?string $navigationGroup = 'Konfiguration';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([//
                TextInput::make('name')
                    ->columnSpanFull()
                    ->required(),

                Section::make('Empfänger')
                    ->columns(2)
                    ->schema([
                        Repeater::make('to')
                            ->label('An')
                            ->minItems(1)
                            ->simple(
                                TextInput::make('to_address')
                                    ->required()
                                    ->email()
                                    ->hiddenLabel(),
                            )->addActionLabel('Empfänger hinzufügen'),

                        Repeater::make('cc')
                            ->label('Kopie an')
                            ->simple(
                                TextInput::make('cc_address')
                                    ->required()
                                    ->email()
                                    ->hiddenLabel(),
                            )->defaultItems(0)
                            ->addActionLabel('Empfänger hinzufügen'),
                    ]),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?ContactFormTopics $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?ContactFormTopics $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),

                TextColumn::make('to')
                    ->listWithLineBreaks(),

                TextColumn::make('cc')
                    ->listWithLineBreaks(),
            ])
            ->filters([
                //
            ])
            ->actions([
                DownStepAction::make()->iconButton(),
                UpStepAction::make()->iconButton(),
                EditAction::make(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactFormTopics::route('/'),
            'create' => Pages\CreateContactFormTopics::route('/create'),
            'edit' => Pages\EditContactFormTopics::route('/{record}/edit'),
        ];
    }
}
