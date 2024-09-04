<?php

namespace App\Filament\Resources;

use App\Enums\ConferenceStatus;
use App\Filament\Resources\ConferenceResource\Pages;
use App\Models\Conference;
use App\Models\Venue;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ConferenceResource extends Resource
{
    protected static ?string $model = Conference::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Location'))
                    ->icon('heroicon-o-map')
                    ->schema([
                        Forms\Components\Select::make('venue_id')
                            ->relationship('venue', 'name')
                            ->columnSpanFull()
                            ->searchable()
                            ->preload()
                            ->createOptionForm(
                                VenueResource::getFormSchema()
                            )
                            ->getOptionLabelFromRecordUsing(fn(Venue $venue): string => "{$venue->name} - {$venue->city} ({$venue->state}) - {$venue->country}")
                            ->required(),
                    ]),
                Forms\Components\Section::make(__('General Information'))
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state ?? '')))
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->required(),
                        Forms\Components\MarkdownEditor::make('description')
                            ->disableToolbarButtons([
                                'attachFiles'
                            ])
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make(__('Dates'))
                    ->icon('heroicon-o-calendar-date-range')
                    ->columns(2)
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->required(),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->required(),
                    ]),
                Forms\Components\Radio::make('status')
                    ->options(
                        ConferenceStatus::class
                    )
                    ->required(),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('venue.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->icon(fn($record) => $record->status->getIcon())
                    ->color(fn($record) => $record->status->getColor())
                    ->tooltip(fn($record) => $record->status->value)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ConferenceStatus::class)
                    ->searchable(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                    // publish a draft/archived conference
                    Tables\Actions\Action::make(__('Publish'))
                        ->icon('heroicon-o-eye')
                        ->visible(fn($record) => $record->status === ConferenceStatus::Draft || $record->status === ConferenceStatus::Archived)
                        ->action(fn($record) => $record->update(['status' => ConferenceStatus::Published])),

                    // unpublish a published conference
                    Tables\Actions\Action::make(__('Unpublish'))
                        ->icon('heroicon-o-eye-slash')
                        ->visible(fn($record) => $record->status === ConferenceStatus::Published)
                        ->action(fn($record) => $record->update(['status' => ConferenceStatus::Draft])),

                    // archive an unarchived conference
                    Tables\Actions\Action::make(__('Archive'))
                        ->icon('heroicon-o-archive-box')
                        ->visible(fn($record) => $record->status !== ConferenceStatus::Archived && $record->status !== ConferenceStatus::Cancelled)
                        ->action(fn($record) => $record->update(['status' => ConferenceStatus::Archived])),

                    // cancel an umpublished and uncancelled conference
                    Tables\Actions\Action::make(__('Cancel'))
                        ->icon('heroicon-c-x-circle')
                        ->visible(fn($record) => $record->status !== ConferenceStatus::Cancelled && $record->status !== ConferenceStatus::Published)
                        ->action(fn($record) => $record->update(['status' => ConferenceStatus::Cancelled])),

                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListConferences::route('/'),
            'create' => Pages\CreateConference::route('/create'),
            'edit'   => Pages\EditConference::route('/{record}/edit'),
        ];
    }
}
