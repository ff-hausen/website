<?php

namespace App\Filament\Resources\Calendar\Events;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use App\Filament\Resources\Calendar\Events\Pages\ListEvents;
use App\Filament\Resources\Calendar\Events\Pages\CreateEvent;
use App\Filament\Resources\Calendar\Events\Pages\EditEvent;
use App\Filament\Resources\Calendar\EventResource\Pages;
use App\Models\Calendar\Department;
use App\Models\Calendar\Event;
use App\Models\Calendar\Type;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $slug = 'calendar/events';

    protected static string | \UnitEnum | null $navigationGroup = 'Dienstplan';

    protected static ?string $navigationLabel = 'Termine';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $modelLabel = 'Termin';

    protected static ?string $pluralModelLabel = 'Termine';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([//
                Section::make('Termindetails')
                    ->schema([
                        TextInput::make('title')
                            ->translateLabel()
                            ->required()
                            ->autofocus()
                            ->columnSpanFull(),

                        Group::make([
                            DateTimePicker::make('start_time')
                                ->translateLabel()
                                ->seconds(false)
                                ->required()
                                ->live()
                                ->default(function () {
                                    $time = session('event')?->start_time;
                                    if ($time) {
                                        return Carbon::parse($time)->addWeek()->toDateTimeLocalString();
                                    }
                                })
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    if (! $state) {
                                        return;
                                    }

                                    $time = Carbon::parse($state);

                                    if ($time->hour !== 0 || $time->minute !== 0) {
                                        $set('all_day', false);
                                    }

                                    if (! $get('all_day')) {
                                        $set('end_time', $time->addHours(2)->toDateTimeLocalString());
                                    }

                                }),

                            DateTimePicker::make('end_time')
                                ->translateLabel()
                                ->seconds(false)
                                ->nullable()
                                ->live()
                                ->default(function () {
                                    $time = session('event')?->end_time;
                                    if ($time) {
                                        return Carbon::parse($time)->addWeek()->toDateTimeLocalString();
                                    }
                                })
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    if (! $state) {
                                        return;
                                    }

                                    $time = Carbon::parse($state);

                                    if ($get('all_day')) {
                                        $set('end_time', $time->setTime(0, 0)->toDateTimeLocalString());
                                    }
                                }),

                            Checkbox::make('all_day')
                                ->translateLabel()
                                ->live()
                                ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                    if ($state) {
                                        $set('start_time', Carbon::parse($get('start_time'))->setTime(0, 0)->toDateTimeLocalString());
                                        $set('end_time', Carbon::parse($get('end_time'))->setTime(0, 0)->toDateTimeLocalString());
                                    }
                                }),
                        ])->columnSpanFull()
                            ->columns(2),

                        MarkdownEditor::make('description')
                            ->translateLabel()
                            ->nullable()
                            ->disableToolbarButtons([
                                'heading',
                            ]),
                    ]),

                Section::make([
                    Select::make('department')
                        ->translateLabel()
                        ->options(self::departmentOptions())
                        ->default(fn () => session('event')?->department ?? request()->query('department'))
                        ->required()
                        ->live(),

                    Select::make('type')
                        ->translateLabel()
                        ->relationship(titleAttribute: 'name', modifyQueryUsing: fn (Builder $query, Get $get) => $query->whereDepartment($get('department')))
                        ->nullable()
                        ->createOptionForm([
                            TextInput::make('name'),
                        ])
                        ->default(fn () => session('event')?->type_id)
                        ->createOptionUsing(function (array $data, Get $get): int {
                            return Type::create([
                                ...$data,
                                'department' => $get('department'),
                            ])->getKey();
                        }),

                    Select::make('responsible')
                        ->translateLabel()
                        ->relationship(titleAttribute: 'email')
                        ->multiple()
                        ->preload()
                        ->searchable(['first_name', 'last_name', 'email'])
                        ->getOptionLabelFromRecordUsing(fn (User $user) => "$user->first_name $user->last_name <$user->email>"),
                ])
                    ->columnSpanFull()
                    ->columns(2),

                Placeholder::make('created_at')
                    ->label('Erstellt')
                    ->content(fn (?Event $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Zuletzt bearbeitet')
                    ->content(fn (?Event $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('start_time')
                    ->translateLabel()
                    ->dateTime()
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state, Event $record) => $record->all_day ? $state->format('d.m.Y') : $state->format('d.m.Y H:i')),

                TextColumn::make('end_time')
                    ->translateLabel()
                    ->dateTime()
                    ->formatStateUsing(fn ($state, Event $record) => $record->all_day ? $state->format('d.m.Y') : $state->format('d.m.Y H:i'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('type.name')
                    ->translateLabel()
                    ->sortable(),

                TextColumn::make('responsible.full_name')
                    ->translateLabel()
                    ->listWithLineBreaks(),

                TextColumn::make('createdBy.full_name')
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('start_time', 'asc')
            ->filters([
                Filter::make('only_upcoming')
                    ->label('nur anstehende Termine')
                    ->baseQuery(fn (Builder $query) => $query->upcoming())
                    ->toggle()
                    ->default()
                    ->indicateUsing(fn () => null),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['createdBy']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'createdBy.email'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->createdBy) {
            $details['CreatedBy'] = $record->createdBy->email;
        }

        return $details;
    }

    public static function departmentOptions(): array
    {
        $departmentOptions = [];

        $user = auth()->user();

        foreach (Department::cases() as $case) {
            if ($case->canCreate($user)) {
                $departmentOptions += [$case->value => $case->getLabel()];
            }
        }

        return $departmentOptions;
    }
}
