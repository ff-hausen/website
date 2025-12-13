<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers\PermissionsRelationManager;
use App\Filament\Resources\RoleResource\RelationManagers\UsersRelationManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $slug = 'roles';

    protected static ?string $modelLabel = 'Rolle';

    protected static ?string $pluralModelLabel = 'Rollen';

    protected static ?string $navigationLabel = 'Rollen';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShieldCheck;

    protected static \UnitEnum|string|null $navigationGroup = 'Nutzerverwaltung';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->translateLabel()
                    ->required(),

                TextInput::make('wiki_name')
                    ->translateLabel(),

                Checkbox::make('show_in_userlist')
                    ->translateLabel(),

                Grid::make()
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('created_at')
                            ->translateLabel()
                            ->label('Created Date')
                            ->state(fn (?Role $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                        TextEntry::make('updated_at')
                            ->translateLabel()
                            ->label('Last Modified Date')
                            ->state(fn (?Role $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('wiki_name')
                    ->translateLabel()
                    ->sortable(),

                TextColumn::make('users_count')
                    ->translateLabel()
                    ->numeric()
                    ->counts('users')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->iconButton(),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::make(),
            PermissionsRelationManager::make(),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
