<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AusflugParticipantResource\Pages;
use App\Mail\Ausflug\AnmeldungConfirmationMail;
use App\Models\AusflugParticipant;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Mail;

class AusflugParticipantResource extends Resource
{
    protected static ?string $model = AusflugParticipant::class;

    protected static ?string $modelLabel = 'Vereinsausflug Teilnehmer:innen';

    protected static ?string $pluralModelLabel = 'Vereinsausflug Teilnehmer:innen';

    protected static ?string $slug = 'ausflug-participants';

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Vereinsausflug Teilnehmer:innen';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                TextInput::make('name')
                    ->columnSpanFull()
                    ->required(),

                Fieldset::make('Adresse')
                    ->columns(3)
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('street')
                            ->label('Straße')
                            ->columnSpanFull()
                            ->required(),

                        TextInput::make('zip_code')
                            ->label('PLZ')
                            ->columnSpan(1)
                            ->required(),

                        TextInput::make('city')
                            ->label('Stadt')
                            ->columnSpan(2)
                            ->required(),
                    ]),

                Fieldset::make('Kontakt')
                    ->columnSpan(1)
                    ->columns(1)
                    ->schema([
                        TextInput::make('email')
                            ->label('E-Mail'),

                        TextInput::make('phone')
                            ->label('Telefon'),
                    ]),

                Radio::make('type')
                    ->label('Vereinsmitgliedschaft')
                    ->inline()
                    ->inlineLabel(false)
                    ->options([
                        'ea' => 'Einsatzabteilung',
                        'verein' => 'Verein/Freunde',
                    ])
                    ->descriptions([
                        'ea' => '90 €',
                        'verein' => '150 €',
                    ])
                    ->live()
                    ->afterStateUpdated(function (Set $set, string $state) {
                        $price = match ($state) {
                            'ea' => 90,
                            'verein' => 150,
                        };

                        $set('price', $price);
                    }),

                TextInput::make('price')
                    ->suffix('€')
                    ->numeric(),

                Placeholder::make('created_at')
                    ->label('Created Date')
                    ->columnStart(1)
                    ->content(fn (?AusflugParticipant $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label('Last Modified Date')
                    ->content(fn (?AusflugParticipant $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('submission_id')
                    ->label('Anmeldung'),
            ])
            ->columns([
                Split::make([
                    IconColumn::make('primary')
                        ->boolean()
                        ->trueIcon('heroicon-s-star')
                        ->trueColor('warning')
                        ->falseIcon('heroicon-o-minus')
                        ->falseColor('gray')
                        ->size(IconColumn\IconColumnSize::Medium)
                        ->grow(false),

                    TextColumn::make('name')
                        ->label('Name')
                        ->weight(FontWeight::Bold)
                        ->searchable()
                        ->sortable(),

                    Stack::make([
                        TextColumn::make('street')
                            ->label('Adresse'),

                        TextColumn::make('city')
                            ->getStateUsing(fn (AusflugParticipant $participant) => $participant->zip_code.' '.$participant->city),
                    ]),

                    Stack::make([
                        TextColumn::make('email')
                            ->icon('heroicon-o-envelope')
                            ->searchable()
                            ->sortable(),

                        TextColumn::make('phone')
                            ->icon('heroicon-o-phone'),
                    ]),

                    Stack::make([
                        TextColumn::make('type')
                            ->badge()
                            ->formatStateUsing(fn (AusflugParticipant $p) => $p->typeLocale())
                            ->color(fn (string $state): string => match ($state) {
                                'ea' => 'danger',
                                'verein' => 'warning',
                            }),

                        TextColumn::make('price')
                            ->icon('heroicon-o-currency-euro')
                            ->suffix(' €')
                            ->summarize(
                                Sum::make()
                                    ->money('EUR')
                                    ->query(fn (QueryBuilder $query) => $query->where('verified', true))
                            ),
                    ]),

                    IconColumn::make('verified')
                        ->label('Verifiziert')
                        ->boolean()
                        ->grow(false),
                ]),
            ])
            ->filters([
                TernaryFilter::make('verified')
                    ->label('Verifiziert')
                    ->default(true),

                SelectFilter::make('type')
                    ->options([
                        'ea' => 'Einsatzabteilung',
                        'verein' => 'Verein/Freunde',
                    ]),
            ])
            ->actions([
                Action::make('summary')
                    ->label('Zusammenfassung')
                    ->icon('heroicon-o-list-bullet')
                    ->url(fn (AusflugParticipant $participant) => $participant->summaryUrl())
                    ->openUrlInNewTab()
                    ->visible(fn (AusflugParticipant $participant) => $participant->verified),

                Action::make('resend_verification')
                    ->label('Verifizierung neustarten')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (AusflugParticipant $participant) {
                        $participants = AusflugParticipant::whereSubmissionId($participant->submission_id)->get();
                        $primary = $participants->where('primary', true)->first();

                        Mail::to($primary->email)->send(new AnmeldungConfirmationMail($participants));
                    })
                    ->requiresConfirmation()
                    ->visible(fn (AusflugParticipant $participant) => ! $participant->verified),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAusflugParticipants::route('/'),
            'create' => Pages\CreateAusflugParticipant::route('/create'),
            'edit' => Pages\EditAusflugParticipant::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
