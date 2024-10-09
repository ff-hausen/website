<?php

namespace App\Filament\Resources\Calendar;

use App\Filament\Resources\Calendar\EventResource\Pages;
use App\Models\Calendar\Department;
use App\Models\Calendar\Event;
use App\Models\Calendar\Type;
use App\Models\Scopes\UpcomingScope;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
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

    protected static ?string $navigationGroup = 'Dienstplan';

    protected static ?string $navigationLabel = 'Termine';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $modelLabel = 'Termin';

    protected static ?string $pluralModelLabel = 'Termine';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([//
                Section::make('Termindetails')
                    ->schema([
                        TextInput::make('title')
                            ->translateLabel()
                            ->required()
                            ->columnSpanFull(),

                        Group::make([
                            DateTimePicker::make('start_time')
                                ->translateLabel()
                                ->seconds(false)
                                ->required()
                                ->live()
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
                    ]),

                Section::make([
                    Select::make('department')
                        ->translateLabel()
                        ->options(self::departmentOptions())
                        ->default(fn () => request()->query('department'))
                        ->required()
                        ->live(),

                    Select::make('type')
                        ->translateLabel()
                        ->relationship(titleAttribute: 'name', modifyQueryUsing: fn (Builder $query, Get $get) => $query->whereDepartment($get('department')))
                        ->nullable()
                        ->createOptionForm([
                            TextInput::make('name'),
                        ])
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
                Filter::make('with_past')
                    ->label('Zeige vergangene Termine')
                    ->baseQuery(fn (Builder $query) => $query->withoutGlobalScope(UpcomingScope::class))
                    ->toggle(),
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
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

    public static function typeOptions(): array
    {
        return Event::select('type')->distinct()->pluck('type')->toArray();
    }
}
