<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VenueResource\Pages;
use App\Models\Venue;
use Countries;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VenueResource extends Resource
{
    protected static ?string $model = Venue::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->sortable()
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
                SelectFilter::make('country')
                    ->multiple()
                    ->searchable()
                    ->options(Countries::getList(app()->getLocale())),
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
            'index'  => Pages\ListVenues::route('/'),
            'create' => Pages\CreateVenue::route('/create'),
            'edit'   => Pages\EditVenue::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<Forms\Components\Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make(__('General Information'))
                ->icon('heroicon-o-information-circle')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->columnSpanFull(),
                ]),
            Forms\Components\Section::make(__('Location'))
                ->description(__('Address information for this venue.'))
                ->icon('heroicon-o-map')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('address')
                        ->columnSpanFull()
                        ->required(),
                    Forms\Components\TextInput::make('city')
                        ->required(),
                    Forms\Components\TextInput::make('state')
                        ->required(),
                    Forms\Components\TextInput::make('zip')
                        ->required(),
                    Forms\Components\Select::make('country')
                        ->placeholder(__('Select a country'))
                        ->searchable()
                        ->options(
                            Countries::getList(app()->getLocale())
                        )
                        ->required(),
                ]),
        ];
    }
}
