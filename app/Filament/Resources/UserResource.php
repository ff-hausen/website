<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'users';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    protected static \UnitEnum|string|null $navigationGroup = 'Nutzerverwaltung';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->columnSpanFull()
                    ->required(),

                Grid::make()
                    ->columns(1)
                    ->schema([
                        TextInput::make('email')
                            ->required(),

                        DatePicker::make('email_verified_at')
                            ->label('Email Verified Date'),
                    ]),

                Grid::make()
                    ->columns(1)
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->live()
                            // Required on creation
                            ->required(fn (string $operation) => $operation === 'create')
                            ->hint(fn (string $operation) => ($operation === 'edit')
                                ? 'Passwort wird nur geändert, wenn Feld gesetzt ist.'
                                : null
                            )
                            ->revealable()
                            ->suffixAction(Action::make('Generate')->icon(Heroicon::ArrowPath)->action(
                                fn (Set $schemaSet) => $schemaSet('password', Str::password(16))
                            ))
                            ->copyable()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state)),

                        DatePicker::make('user_verified_at')
                            ->label('User Verified Date'),
                    ]),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->state(fn (?User $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->state(fn (?User $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                TextEntry::make('Passwortänderungshinweis')
                    ->hiddenLabel()
                    ->color('warning')
                    ->icon(Heroicon::ExclamationTriangle)
                    ->iconColor('warning')
                    ->state('Das Passwort des Nutzers wird geändert.')
                    ->visible(fn (string $operation, Get $get) => $operation === 'edit' && filled($get('password'))),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user_verified_at')
                    ->label('User Verified Date')
                    ->date(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
