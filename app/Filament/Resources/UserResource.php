<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Role;
use App\Models\RoleName;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Benutzer';

    protected static ?string $navigationGroup = 'Benutzerverwaltung';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'email'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            $record->email,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('username')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->translateLabel()
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->translateLabel()
                    ->disabled(),

                Forms\Components\Fieldset::make('Rollen')
                    ->schema([
                        Forms\Components\CheckboxList::make('roles')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->relationship()
                            ->getOptionLabelFromRecordUsing(fn (Role $role) => $role->name->value),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->translateLabel()
                    ->searchable(),

                Tables\Columns\TextColumn::make('username')
                    ->translateLabel()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('email')
                    ->translateLabel()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('email_verified')
                    ->label('E-Mail verifiziert')
                    ->state(fn (User $user) => $user->email_verified_at !== null)
                    ->tooltip(fn ($state) => $state ? 'E-Mail wurde verifiziert' : 'E-Mail wurde nicht verifiziert')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Abteilungen')
                    ->badge()
                    ->state(fn (User $user) => $user->role_names->filter(fn ($item) => in_array($item, [
                        RoleName::Einsatzabteilung,
                        RoleName::Vereinsmitglied,
                        RoleName::AltersUndEhrenabteilung,
                    ])))
                    ->color(fn ($state) => match ($state) {
                        RoleName::Einsatzabteilung => Color::Red,
                        RoleName::Vereinsmitglied => Color::Amber,
                        RoleName::AltersUndEhrenabteilung => Color::Green,
                        default => 'gray',
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
