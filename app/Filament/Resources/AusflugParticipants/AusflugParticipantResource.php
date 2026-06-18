<?php

namespace App\Filament\Resources\AusflugParticipants;

use App\Filament\Resources\AusflugParticipants\Pages\CreateAusflugParticipant;
use App\Filament\Resources\AusflugParticipants\Pages\EditAusflugParticipant;
use App\Filament\Resources\AusflugParticipants\Pages\ListAusflugParticipants;
use App\Filament\Resources\AusflugParticipants\Pages\ViewAusflugParticipant;
use App\Jobs\NotifyAusflugParticipantsPaid;
use App\Mail\Ausflug\AnmeldungVerificationMail;
use App\Models\AusflugParticipant;
use App\Models\RoleName;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
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

    protected static ?string $recordTitleAttribute = 'name';

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('name')
                    ->columnSpanFull(),

                Fieldset::make('Adresse')
                    ->columnSpan(1)
                    ->columns(3)
                    ->schema([
                        TextEntry::make('street')
                            ->columnSpanFull(),

                        TextEntry::make('zip_code')
                            ->columnSpan(1),

                        TextEntry::make('city')
                            ->columnSpan(2),
                    ]),

                Fieldset::make('Kontakt')
                    ->columns(1)
                    ->schema([
                        TextEntry::make('email')
                            ->icon(fn (AusflugParticipant $p) => $p->verified ? Heroicon::CheckBadge : Heroicon::ExclamationCircle)
                            ->iconColor(fn (AusflugParticipant $p) => $p->verified ? 'success' : 'danger')
                            ->tooltip(fn (AusflugParticipant $p) => sprintf(
                                'Anmeldung bestätigt: %s',
                                $p->verified ? 'Ja' : 'Nein'
                            ))
                            ->default('-'),

                        TextEntry::make('phone')
                            ->default('-'),
                    ]),

                Fieldset::make('Bezahlung')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('type')
                            ->label('Vereinsmitgliedschaft')
                            ->badge()
                            ->formatStateUsing(fn (AusflugParticipant $p) => $p->typeLocale())
                            ->color(fn (string $state): string => match ($state) {
                                'ea' => 'danger',
                                'verein' => 'warning',
                            }),

                        TextEntry::make('price')
                            ->label('Betrag')
                            ->money('EUR')
                            ->columnStart(1),

                        TextEntry::make('paid_at')
                            ->date('d. F Y')
                            ->beforeContent([
                                IconEntry::make('paid_at')
                                    ->hiddenLabel()
                                    ->getStateUsing(fn (AusflugParticipant $p) => ! is_null($p->paid_at))
                                    ->tooltip(fn (AusflugParticipant $p) => is_null($p->paid_at) ? 'noch nicht bezahlt' : '')
                                    ->boolean()
                                    ->trueIcon(Heroicon::CheckCircle)
                                    ->trueColor(Color::Green)
                                    ->falseIcon(Heroicon::OutlinedXCircle)
                                    ->falseColor(Color::Red),
                            ]),
                    ]),
            ]);
    }

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
                    ->columnStart(1)
                    ->numeric(),

                DatePicker::make('paid_at')
                    ->label('Bezahlt am')
                    ->nullable()
                    ->hint('Änderungen lösen keine E-Mail Benachrichtigung aus.'),

                TextEntry::make('created_at')
                    ->label('Created Date')
                    ->columnStart(1)
                    ->state(fn (?AusflugParticipant $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                TextEntry::make('updated_at')
                    ->label('Last Modified Date')
                    ->state(fn (?AusflugParticipant $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('submission_id')
                    ->label('Anmeldung')
                    // introduces n+1 problems
                    ->getTitleFromRecordUsing(fn (AusflugParticipant $p) => AusflugParticipant::whereSubmissionId($p->submission_id)->wherePrimary(true)->first()->name),
            ])
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->icon(fn (AusflugParticipant $record) => $record->primary ? Heroicon::Star : null)
                    ->iconColor(fn (AusflugParticipant $record) => $record->primary ? 'warning' : 'gray')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Typ')
                    ->badge()
                    ->formatStateUsing(fn (AusflugParticipant $p) => $p->typeLocale())
                    ->color(fn (string $state): string => match ($state) {
                        'ea' => 'danger',
                        'verein' => 'warning',
                    }),

                TextColumn::make('price')
                    ->label('Betrag')
                    ->icon(fn (AusflugParticipant $record) => is_null($record->paid_at) ? Heroicon::OutlinedCurrencyEuro : Heroicon::CurrencyEuro)
                    ->iconColor(fn (AusflugParticipant $record) => is_null($record->paid_at) ? 'danger' : 'success')
                    ->tooltip(fn (AusflugParticipant $record) => is_null($record->paid_at) ? 'noch nicht bezahlt' : 'Bezahlt am '.$record->paid_at->format('d.m.Y'))
                    ->money('EUR')
                    ->summarize(
                        Sum::make()
                            ->money('EUR')
                            ->query(fn (QueryBuilder $query) => $query->where('verified', true))
                    ),

                IconColumn::make('verified')
                    ->label('Bestätigt')
                    ->tooltip('Hat die Anmeldung per Mail bestätigt')
                    ->boolean()
                    ->trueIcon(Heroicon::CheckBadge)
                    ->grow(false),
            ])
            ->filters([
                TernaryFilter::make('verified')
                    ->label('Bestätigt')
                    ->default(true),

                TernaryFilter::make('paid_at')
                    ->label('Bezahlt')
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
                    ->tooltip('Zeigt die Zusammenfassung an')
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
            'view' => ViewAusflugParticipant::route('/{record}'),
            'edit' => EditAusflugParticipant::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
