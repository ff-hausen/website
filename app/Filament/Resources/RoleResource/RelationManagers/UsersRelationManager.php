<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('username')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('username')
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('last_name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('email')
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->modelLabel('Benutzer')
                    ->translateLabel()
                    ->recordTitle(fn (User $user) => $user->full_name)
                    ->recordSelectSearchColumns(['first_name', 'last_name', 'email'])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->recordTitle(fn (User $user) => $user->full_name),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
