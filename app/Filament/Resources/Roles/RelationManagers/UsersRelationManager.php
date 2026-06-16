<?php

namespace App\Filament\Resources\Roles\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use App\Models\User;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static ?string $title = 'Benutzer';

    protected static ?string $modelLabel = 'Benutzer';

    protected static ?string $pluralModelLabel = 'Benutzer';

    protected static string $relationship = 'users';

    public function form(Schema $schema): Schema
    {
        return $schema;
    }

    public function table(Table $table): Table
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
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordTitle(fn (User $user) => "$user->full_name <$user->email>")
                    ->recordSelectSearchColumns(['first_name', 'last_name', 'email'])
                    ->multiple(),
            ])
            ->recordActions([
                DetachAction::make()
                    ->recordTitle(fn (User $user) => $user->full_name),
            ])
            ->toolbarActions([
                DetachBulkAction::make(),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
