<?php

namespace App\Filament\Resources\SpeakerResource\RelationManagers;

use App\Filament\Resources\TalkResource;
use App\Models\Speaker;
use App\Models\Talk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TalksRelationManager extends RelationManager
{
    protected static string $relationship = 'talks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        /** @var Speaker $speaker */
        $speaker = $this->getOwnerRecord();

        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('talkCategory.name')
                    ->label(__('Category'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->numeric()
                    ->suffix(' min')
                    ->icon('heroicon-o-clock')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->tooltip(fn(Talk $record) => $record->type->getDescription())
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                ]),

            ])
            ->bulkActions([

            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TalkResource::talkInformationInfolistSection()
            ]);
    }
}
