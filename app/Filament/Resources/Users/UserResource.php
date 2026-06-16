<?php

namespace App\Filament\Resources\Users;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages;
use App\Models\Role;
use App\Models\RoleName;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Benutzer';

    protected static ?string $pluralModelLabel = 'Benutzer';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Benutzer';

    protected static string | \UnitEnum | null $navigationGroup = 'Benutzerverwaltung';

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

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('last_name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->translateLabel()
                    ->email()
                    ->required()
                    ->maxLength(255),

                DateTimePicker::make('email_verified_at')
                    ->translateLabel()
                    ->disabled(),

                Fieldset::make('Rollen')
                    ->schema([
                        CheckboxList::make('roles')
                            ->hiddenLabel()
                            ->columns(4)
                            ->columnSpanFull()
                            ->relationship(modifyQueryUsing: fn (Builder $query) => $query->orderBy('name', 'asc'))
                            ->getOptionLabelFromRecordUsing(fn (Role $role) => $role->name->value),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('last_name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->translateLabel()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                IconColumn::make('email_verified_at')
                    ->label('E-Mail verifiziert')
                    ->state(fn (User $user) => $user->email_verified_at !== null)
                    ->tooltip(fn ($state) => $state ? 'E-Mail wurde verifiziert' : 'E-Mail wurde nicht verifiziert')
                    ->boolean()
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('roles.name')
                    ->label('Abteilungen')
                    ->badge()
                    ->state(fn (User $user) => $user->roles->pluck('name')->filter(fn ($item) => in_array($item, [
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

                TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
