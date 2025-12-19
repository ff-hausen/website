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
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tomloprod\Colority\Support\Facades\Colority;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'users';

    protected static ?string $modelLabel = 'Benutzer';

    protected static ?string $pluralModelLabel = 'Benutzer';

    protected static ?string $navigationLabel = 'Benutzer';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;

    protected static \UnitEnum|string|null $navigationGroup = 'Nutzerverwaltung';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->translateLabel()
                    ->required(),

                TextInput::make('last_name')
                    ->translateLabel(),

                Grid::make()
                    ->columns(1)
                    ->schema([
                        TextInput::make('email')
                            ->translateLabel()
                            ->required(),

                        DatePicker::make('email_verified_at')
                            ->label('Email Verified Date')
                            ->translateLabel()
                            ->nullable(),
                    ]),

                Grid::make()
                    ->columns(1)
                    ->schema([
                        TextInput::make('password')
                            ->translateLabel()
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

                        DatePicker::make('user_approved_at')
                            ->label('User Verified Date')
                            ->translateLabel()
                            ->nullable(),
                    ]),

                Section::make('Roles')
                    ->translateLabel()
                    ->columnSpanFull()
                    ->schema([

                        CheckboxList::make('roles')
                            ->hiddenLabel()
                            ->columns(3)
                            ->relationship(titleAttribute: 'name'),

                    ]),

                TextEntry::make('created_at')
                    ->translateLabel()
                    ->label('Created Date')
                    ->state(fn (?User $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->translateLabel()
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
                IconColumn::make('user_approved_at')
                    ->label('Active')
                    ->translateLabel()
                    ->boolean()
                    ->state(fn (User $user) => $user->isUserApproved())
                    ->tooltip(fn (User $user) => $user->isUserApproved() ? __('User is approved') : __('User is not approved'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('first_name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('last_name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('email')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('roles')
                    ->label('Divisions')
                    ->translateLabel()
                    ->badge()
                    ->state(fn (User $user) => $user->roles()->where('show_in_userlist', true)->get()->pluck('name')->sort())
                    ->color(fn ($state) => Color::hex(Colority::textToColor($state)->toHex()->getValueColor()))
                    ->listWithLineBreaks()
                    ->toggleable(),

            ])
            ->filters([
                TernaryFilter::make('user_approved_at')
                    ->translateLabel()
                    ->nullable(),
            ])
            ->recordActions([
                Action::make('Approve User')
                    ->label('Approve User')
                    ->translateLabel()
                    ->icon(Heroicon::CheckBadge)
                    ->visible(fn (User $user): bool => ! $user->isUserApproved())
                    ->authorize('approve')
                    ->action(function (User $user) {
                        $user->forceFill([
                            'user_approved_at' => now(),
                        ])->save();
                    }),
                EditAction::make()
                    ->iconButton(),
                DeleteAction::make()
                    ->iconButton(),
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
        return ['first_name', 'last_name', 'email'];
    }
}
