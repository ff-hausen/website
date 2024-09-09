<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\RoleName;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Benutzer';

    protected static ?string $navigationGroup = 'Benutzerverwaltung';

    protected static ?int $navigationSort = 1;

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->full_name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'email'];
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
                            ->relationship(titleAttribute: 'name'),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->translateLabel()
                    ->searchable(),

                Tables\Columns\IconColumn::make('einsatzabteilung')
                    ->label('EA')
                    ->tooltip('Einsatzabteilung')
                    ->state(fn (User $user) => $user->roles->where('name', RoleName::Einsatzabteilung->value)->isNotEmpty())
                    ->icon(fn (bool $state) => $state ? 'heroicon-s-check-circle' : 'heroicon-s-x-circle')
                    ->color(fn (bool $state) => $state ? 'success' : 'danger'),

                Tables\Columns\IconColumn::make('verein')
                    ->label('V')
                    ->tooltip('Verein')
                    ->state(fn (User $user) => $user->roles->where('name', RoleName::Vereinsmitglied->value)->isNotEmpty())
                    ->icon(fn (bool $state) => $state ? 'heroicon-s-check-circle' : 'heroicon-s-x-circle')
                    ->color(fn (bool $state) => $state ? 'success' : 'danger'),

                Tables\Columns\IconColumn::make('alters_ehrenabteilung')
                    ->label('EM')
                    ->tooltip('Alters- und Ehrenabteilung')
                    ->state(fn (User $user) => $user->roles->where('name', RoleName::AltersUndEhrenabteilung->value)->isNotEmpty())
                    ->icon(fn (bool $state) => $state ? 'heroicon-s-check-circle' : 'heroicon-s-x-circle')
                    ->color(fn (bool $state) => $state ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
