<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConferenceResource\Pages;
use App\Models\Conference;
use App\Models\Venue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
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
                Forms\Components\Section::make(__('General Information'))
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
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
                Forms\Components\TextInput::make('status')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
