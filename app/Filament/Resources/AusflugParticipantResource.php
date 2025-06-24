<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AusflugParticipantResource\Pages;
use App\Models\AusflugParticipant;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AusflugParticipantResource extends Resource
{
    protected static ?string $model = AusflugParticipant::class;

    protected static ?string $slug = 'ausflug-participants';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),

                TextInput::make('street')
                    ->required(),

                TextInput::make('zip_code')
                    ->required(),

                TextInput::make('city')
                    ->required(),

                TextInput::make('email'),

                TextInput::make('phone'),

                TextInput::make('type')
                    ->required(),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->content(fn (?AusflugParticipant $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?AusflugParticipant $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('street'),

                TextColumn::make('zip_code'),

                TextColumn::make('city'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone'),

                TextColumn::make('type'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAusflugParticipants::route('/'),
            'create' => Pages\CreateAusflugParticipant::route('/create'),
            'edit' => Pages\EditAusflugParticipant::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
