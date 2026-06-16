<?php

namespace App\Filament\Resources\AusflugParticipants;

use App\Filament\Resources\AusflugParticipants\Pages\CreateAusflugParticipant;
use App\Filament\Resources\AusflugParticipants\Pages\EditAusflugParticipant;
use App\Filament\Resources\AusflugParticipants\Pages\ListAusflugParticipants;
use App\Jobs\NotifyAusflugParticipantsPaid;
use App\Mail\Ausflug\AnmeldungVerificationMail;
use App\Models\AusflugParticipant;
use App\Models\RoleName;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class AusflugParticipantResource extends Resource
{
    protected static ?string $model = AusflugParticipant::class;

    protected static ?string $modelLabel = 'Vereinsausflug Teilnehmer:innen';

    protected static ?string $pluralModelLabel = 'Vereinsausflug Teilnehmer:innen';

    protected static ?string $slug = 'ausflug-participants';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Vereinsausflug Teilnehmer:innen';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
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
                        ->size(IconSize::Medium)
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
                            ->icon(fn (AusflugParticipant $record) => is_null($record->paid_at) ? Heroicon::OutlinedCurrencyEuro : Heroicon::CurrencyEuro)
                            ->iconColor(fn (AusflugParticipant $record) => is_null($record->paid_at) ? 'danger' : 'success')
                            ->size(TextSize::Medium)
                            ->tooltip(fn (AusflugParticipant $record) => is_null($record->paid_at) ? 'noch nicht bezahlt' : 'Bezahlt am '.$record->paid_at->format('d.m.Y'))
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
                        ->trueIcon(Heroicon::CheckBadge)
                        ->grow(false),
                ]),
            ])
            ->filters([
                TernaryFilter::make('verified')
                    ->label('Verifiziert')
                    ->default(true),

                TernaryFilter::make('paid_at')
                    ->label('bezahlt')
                    ->nullable(),

                SelectFilter::make('type')
                    ->label('Typ')
                    ->options([
                        'ea' => 'Einsatzabteilung',
                        'verein' => 'Verein/Freunde',
                    ]),
            ])
            ->recordActions([
                Action::make('summary')
                    ->hiddenLabel()
                    ->icon('heroicon-o-list-bullet')
                    ->iconButton()
                    ->url(fn (AusflugParticipant $participant) => $participant->summaryUrl())
                    ->openUrlInNewTab(),

                ActionGroup::make([

                    Action::make('mark_paid')
                        ->label('Als bezahlt markieren')
                        ->tooltip('Markiert die Anmeldung als bezahlt')
                        ->size(Size::Medium)
                        ->icon(Heroicon::CreditCard)
                        ->action(function (AusflugParticipant $participant) {
                            AusflugParticipant::whereId($participant->id)->update([
                                'paid_at' => now(),
                            ]);
                            NotifyAusflugParticipantsPaid::dispatch([$participant->id]);
                        })
                        ->requiresConfirmation()
                        ->color(Color::Amber)
                        ->visible(auth()->user()->hasRole(RoleName::Vereinsvorstand)),

                    Action::make('resend_verification')
                        ->label('Verifizierung neustarten')
                        ->tooltip('Verifizierungsmail erneut senden')
                        ->size(Size::Medium)
                        ->icon('heroicon-o-arrow-path')
                        ->action(function (AusflugParticipant $participant) {
                            $participants = AusflugParticipant::whereSubmissionId($participant->submission_id)->get();
                            $primary = $participants->where('primary', true)->first();

                            Mail::to($primary->email)->send(new AnmeldungVerificationMail($participant->submission_id));
                        })
                        ->requiresConfirmation()
                        ->visible(fn (AusflugParticipant $participant) => ! $participant->verified),

                    Action::make('verify')
                        ->label('Freigeben')
                        ->tooltip('Anmeldung manuell freigeben')
                        ->size(Size::Medium)
                        ->icon('heroicon-o-check-badge')
                        ->action(fn (AusflugParticipant $participant) => AusflugParticipant::whereSubmissionId($participant->submission_id)->update(['verified' => true]))
                        ->requiresConfirmation()
                        ->visible(fn (AusflugParticipant $participant) => ! $participant->verified),
                ])->button(),

            ])
            ->toolbarActions([
                BulkAction::make('mark_paid')
                    ->label('Als bezahlt markieren')
                    ->icon(Heroicon::CreditCard)
                    ->tooltip('Markiert die Anmeldung als bezahlt')
                    ->action(function (Collection $selectedRecords) {
                        $ids = $selectedRecords->pluck('id')->toArray();
                        AusflugParticipant::whereIn('id', $ids)->update(['paid_at' => now()]);
                        NotifyAusflugParticipantsPaid::dispatch($ids);
                    })
                    ->requiresConfirmation()
                    ->color(Color::Amber)
                    ->visible(auth()->user()->hasRole(RoleName::Vereinsvorstand)),

                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAusflugParticipants::route('/'),
            'create' => CreateAusflugParticipant::route('/create'),
            'edit' => EditAusflugParticipant::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
